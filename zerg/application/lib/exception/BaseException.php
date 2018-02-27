<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27
 * Time: 23:58
 */

namespace app\lib\exception;


use think\Exception;
use Throwable;

class BaseException extends  Exception
{
   //http状态码404 200
  public $code=400;
  //错误具体信息
  public $msg='参数错误';
  //自定义错误的码
  public $errorCode=10000;
  public function __construct($params=[])
  {
      if(!is_array($params)){
          return;
          //throw new Exception('参数必须是以数组的形式');
      }//参数必须是以数组的形式进行错误的处理
      if(array_key_exists('code', $params)){
        $this->code=$params['code'];
      }
      if(array_key_exists('msg', $params)){
          $this->msg=$params['msg'];
      }
      if(array_key_exists('errorCode', $params)){
          $this->errorCode=$params['errorCode'];
      }
  }


}