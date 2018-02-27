<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/2
 * Time: 19:47
 */

namespace app\lib\exception;


class CatgoryException extends  BaseException
{
    public $code=404;
    public $msg='请求类目不存在';
    public $errorCode=50000;
}