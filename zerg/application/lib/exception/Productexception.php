<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/2
 * Time: 18:57
 */

namespace app\lib\exception;


class Productexception extends  BaseException
{
    public $code=404;
    public $msg='请检查是否存在';
    public $errorCode=20000;
}