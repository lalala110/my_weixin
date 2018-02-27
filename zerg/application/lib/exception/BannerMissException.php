<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28
 * Time: 0:03
 */

namespace app\lib\exception;


class BannerMissException extends  BaseException
{
   public $code=404;
   public $msg='请求Banner不存在';
   public $errorCode=40000;
}