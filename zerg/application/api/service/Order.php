<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29
 * Time: 19:43
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use think\Exception;

class Order
{
    //定义三个成员变量，订单products参数,取出的正是的商品,库存量的检测
   //订单products参数,
    protected $oProducts;

    //从数据库中取出的商品
    protected  $products;
    //获取商品的id
    protected $uid;
    /**
     * @param int $uid 用户id
     * @param array $oProducts 订单商品列表
     * @return array 订单商品状态
     * @throws Exception
     */

    public function place($uid, $oProducts)
    {
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $this->uid = $uid;
        $status = $this->getOrderStatus();//调用返回的结果
        if (!$status['pass']) {
            $status['order_id'] = -1;//订单创建失败
            return $status;
        }
        //创建一个订单
        $orderSnap = $this->snapOrder($status);
        $status = self::createOrder($orderSnap);
        $status['pass'] = true;
        return $status;
    }

    //生成订单的数据,将订单的数据写入数据库里面当中
    private function createOrder($snap){
      //将订单写入数据库当中
        try {
            $orderNo = $this->makeOrderNo();
//            $order = new OrderModel();
            $order=new \app\api\model\Order();//引用模型插入一条语句
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->save();

            $orderID = $order->id;
            $create_time = $order->create_time;

            foreach ($this->oProducts as &$p) {
                $p['order_id'] = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);
            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        } catch (Exception $ex) {
            throw $ex;
        }

    }
    //生成随机订单号码的方法
    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }



   //生成订单快照
    private function snapOrder($status)
    {
        // status可以单独定义一个类
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,//商品的总数量
            'pStatus' => [],//商品的状态
            'snapAddress' => json_encode($this->getUserAddress()),
            'snapName' => $this->products[0]['name'],
            'snapImg' => $this->products[0]['main_img_url'],
        ];
         //判断数组元素大于1，不用全部显示
        if (count($this->products) > 1) {
            $snap['snapName'] .= '等';
        }


        for ($i = 0; $i < count($this->products); $i++) {
            $product = $this->products[$i];
            $oProduct = $this->oProducts[$i];

            $pStatus = $this->snapProduct($product, $oProduct['count']);
            $snap['orderPrice'] += $pStatus['totalPrice'];
            $snap['totalCount'] += $pStatus['count'];
            array_push($snap['pStatus'], $pStatus);
        }
        return $snap;
    }
    //获取用户地址的
    private function getUserAddress()
    {
        $userAddress = UserAddress::where('user_id', '=', $this->uid)
            ->find();
        if (!$userAddress) {
            throw new UserException(
                [
                    'msg' => '用户收货地址不存在，下单失败',
                    'errorCode' => 60001,
                ]);
        }
        return $userAddress->toArray();
    }
     //对外面的方法,库存量的检测,调用已经有的方法
       public function checkOrderStock($orderID)
       {
           $oProducts=OrderProduct::where('order_id','=',$orderID)->select();
           $this->oProducts= $oProducts;//查询出的结果填入到变量里面去
           $this->products=$this->getProductsByOrder($oProducts);
           $status=$this->getOrderStatus();
           return $status;
       }




    //定义一个状态的检测，返回订单的最终的状态,库存量测试
    private function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'pStatusArray' => []
        ];
        //库存量的对比oproduct以及product
        foreach ($this->oProducts as $oProduct) {
            $pStatus =
                $this->getProductStatus(
                    $oProduct['product_id'], $oProduct['count'], $this->products);
            if (!$pStatus['haveStock']) {
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            array_push($status['pStatusArray'], $pStatus);
        }
        return $status;
    }
     //库存量的对比
    private function getProductStatus($oPID, $oCount, $products)
    {
        $pIndex = -1;
        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' => 0
        ];

        for ($i = 0; $i < count($products); $i++) {
            if ($oPID == $products[$i]['id']) {
                $pIndex = $i;
            }//循环遍历序号
        }

        if ($pIndex == -1) {
            // 客户端传递的productid有可能根本不存在
            throw new OrderException(
                [
                    'msg' => 'id为' . $oPID . '的商品不存在，订单创建失败'
                ]);
        } else {//处理商品存在的情况，订单赋值，订单的总价格
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            $pStatus['count'] = $oCount;
            $pStatus['totalPrice'] = $product['price'] * $oCount;
             //库存减去订单的数量
            if ($product['stock'] - $oCount >= 0) {
                $pStatus['haveStock'] = true;
            }
        }
        return $pStatus;
    }

    // 根据订单查找真实商品
    private function getProductsByOrder($oProducts)
    {
        $oPIDs = [];
        //先读取数组id,通过id查询商品的信息
        foreach ($oProducts as $item) {
            array_push($oPIDs, $item['product_id']);
        }
        // 为了避免循环查询数据库,通过id去查询,直接显示想要看到的字段
        $products = Product::all($oPIDs)
            ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
            ->toArray();//数据集转化成为数组
        return $products;
    }
}