<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/5
 * Time: 12:51
 */

namespace app\lib\exception;


class WeChatException  extends  BaseException
{
    public $code=400;
    public $msg='微信服务接口调用失败';
    public $errorCode=999;
}