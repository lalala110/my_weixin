<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29
 * Time: 20:45
 */

namespace app\lib\exception;



class OrderException extends BaseException
{
   public $code=404;
   public $msg='订单不存在的哦';
   public $errorCode=80000;
}