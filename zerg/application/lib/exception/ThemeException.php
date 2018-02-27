<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/2
 * Time: 17:40
 */

namespace app\lib\exception;


class ThemeException extends  BaseException
{
    public $code=400;
    public $msg='请检查主题是否存在';
    public $errorCode=30000;
}