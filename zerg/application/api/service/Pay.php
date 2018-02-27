<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/31
 * Time: 0:01
 */

namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use think\Loader;
use think\Log;

//拼合路径
Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');
//引入没有命名空间的类
class Pay
{
    private $orderID;
    private  $orderNO;
    function __construct($orderID)
    {
        if(!$orderID){
            throw new Exception("订单不允许是空的");
        }
        $this->orderID=$orderID;

    }
    //支付主题的方法
    public function pay()
    {
        //进行库存量的检测
        //订单可能不存在的
        //订单和用户的信息是不匹配的
        //订单已经被支付过了
          $this->checkOrderValid();//检测不过，下面代码不知in个
          $orderService=new OrderService();
          $status=$orderService->checkOrderStock($this->orderID);
          if(!$status["pass"]){
              return $status;
          }
         return  $this->makeWPreOrder($status['orderPrice']);


   }
   //向微信服务端返回结果,发送预订单请求的方法
    private  function makeWPreOrder($totalPrice)
    {
        //要知道用户身份id号码
        $openid=Token::getCurrentTokenVar('openid');
        if(!$openid){
            throw new TokenException();
        }
        $wxOrderData=new \WxPayUnifiedOrder();
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('解忧杂货铺');
        $wxOrderData->SetOpenid($openid);
        //接收微信回调通知
        $wxOrderData->SetNotify_url(config('secure.pay_back_url'));

        return $this->getPaySignature($wxOrderData);
    }
    //向微信请求订单号并生成签名
    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        // 失败时不会返回result_code
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] !='SUCCESS'){
            Log::record($wxOrder,'error');//将异常记录到日志里面
            Log::record('获取预支付订单失败','error');
//            throw new Exception('获取预支付订单失败');
        }
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    //调用签名的方法
    private function  sign($wxOrder){
//         $jsApiPayData=new\WxPayJsApiPay();
//        $jsApiPayData->SetAppid(congig('wx.app_id'));//读取配置文件里面的内容
//        //自动生成时间错
//        $jsApiPayData->SetTimeStamp((string)time());

        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        //生成随机的字符串，并进行md5的加密
        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);
        //下单接口返回prepay_id
        $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $sign = $jsApiPayData->MakeSign();
        //返回给客户端的数组
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;


    }

    //处理模板消息,prepay_id处理
    private  function  recordPreOrder($wxOrder)
    {
        //通过模型的方式更新数据库

      OrderModel::where('id','=',$this->orderID)->update(['prepay_id'=>$wxOrder['prepay_id']]);

    }



   private function  checkOrderValid()
   {
      //数据库的查询,订单的查询
       $order=OrderModel::where('id','=',$this->orderID)->find();
       if(!$order){
           throw new OrderException();
       }
       //查询订单是否与用户匹配
       if(!Token::isValidOperate($order->user_id)){
           throw new TokenException([
              'msg'=>'订单与用户是不匹配的哦'
           ]);
       }
       //订单也有可能被支付过了,1没有被支付
       if($order->status!=OrderStatusEnum::UNPAID){
       throw new OrderException([
          'msg'=>'订单已经成功的支付了',
           'errorCode'=>80003,
           'code'=>400

       ]);
       }
       //返回订单的状态
       $this->orderNO=$order->order_no;
       return true;
   }
}