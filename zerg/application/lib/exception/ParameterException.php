<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28
 * Time: 21:22
 */

namespace app\lib\exception;


class ParameterException extends  BaseException
{

    public $code=400;
    public $msg='参数错误';
    public $errorCode=10000;



}