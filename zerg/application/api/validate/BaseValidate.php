<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/18
 * Time: 23:56
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\exception\ErrorException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
   public function goCheck(){
       //http传入的参数，检测
       $request=Request::instance();//调用静态方法
       $params=$request->param();
       $result=$this->batch()->check($params);//result 记录结果
     if(!$result){
         $e=new ParameterException([
            'msg'=>$this->error,
//            'code'=>400,
//            'errorCode'=>10002
         ]);
//         $e->msg=$this->error;//全局异常处理
//         $e->errorCode=10002;

         throw $e;
//          $error=$this->error;
//          throw new Exception($error);//抛出异常
     }
     else{
         return true;
     }

   }
    protected function isPositiveInteger($value,$rule='',
        $data='',$field=''){//自定义验证规则
        if(is_numeric($value)&&is_int($value+0)&&($value+0)>0){
            return true;
        }
        else{
//            return $field."必须是以正整数的形式";
            return false;
        }
    }
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';//手机z恒则表达式
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    protected function isNotEmpty($value,$rule='',
        $data='',$field=''){//自定义验证规则
        if(empty($value)){
            return false;
        }
        else{
//
            return true;
        }
    }

    public function getDataByRule($arrays)
    {//限制获取的参数变量，比如获取用户地址
        if (array_key_exists('user_id', $arrays) | array_key_exists('uid', $arrays)) {
            // 不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value) {//通过遍历获取指定的参数变量
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }

}