<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/10
 * Time: 23:41
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code=404;
    public $msg='用户是不存在的';
    public $errorCode=60000;
}