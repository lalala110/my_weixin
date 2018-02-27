<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29
 * Time: 0:54
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\OrderPlace;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Controller;
use app\api\service\Order as OrderService;
use app\api\service\Token as TokenService;

class Order extends  BaseController
{
   //提交商品包含选择的商品的信息
    //接口接收到信息后，检测库存量，当库存量不存在的时候,告诉客户端缺货
    //调用支付的接口，就可以调用
    //在进行库存量的检测
    //调用微信的支付接口进行调用
    //返回支付的结果，判断是否成功
    //最后进行库存量的扣除,支付失败返回一个失败的结果
//    protected  function  checkExclusiveScope(){
//        $scope=TokenException::getCurrentTokenVar('scope');
//        if($scope){
//            if($scope ==ScopeEnum::User){
//                return true;
//            }
//            else{
//                throw new ForbiddenException();
//            }
//        }
//        else{
//            throw new TokenException();//当存在的时候才会继续执行,fouze 无效
//        }
//
//    }
     public $beforeActionList=[
         'checkExclusiveScope'=>['only'=>'placeOrder']
     ];
    public  function placeOrder(){
        //用户下单的接口
        (new OrderPlace())->goCheck();
        $products=input('post.products/a');//获取商品的
        $uid=TokenService::getCurrentUid();//获取商品的id
        $order=new OrderService();
        $status=$order->place($uid,$products);
        return $status;

    }

}