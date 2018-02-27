<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/5
 * Time: 23:29
 */

namespace app\lib\exception;


class TokenException extends  BaseException
{
    public $code=401;
    public $msg='Token已经过期了或者没有用了';
    public $errorCode=10001;
}