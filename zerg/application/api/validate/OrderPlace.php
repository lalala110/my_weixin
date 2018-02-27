<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29
 * Time: 19:21
 */

namespace app\api\validate;

//验证数组，下的订单,对具体的字段进行验证
use app\lib\exception\ParameterException;
use think\Exception;
class OrderPlace extends  BaseValidate
{

    protected  $rule=[
       'products'=>'checkProducts'//自定义了一个验证器
   ];//对数组进行验证
    protected $singleRule = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger',
    ];//验证数组下面的子项

   protected  function  checkProducts($values){
       //判断是不是数组
       if(!is_array($values)){
           throw new ParameterException([
               'msg' => '商品参数不正确的哦'
           ]);
       }
       //判断值是不是空的
       if(empty($values)){
           throw new ParameterException([
               'msg' => '商品列表不能为空'
           ]);
       }
       foreach ($values as $value)
       {
           $this->checkProduct($value);
       }
       return true;
   }

    private function checkProduct($value)//验证商品中id，
    {
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);
        if(!$result){
            throw new ParameterException([
                'msg' => '商品列表参数错误',
            ]);
        }

   }
}