<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/5
 * Time: 0:19
 */

namespace app\api\validate;


class TokenGet extends  BaseValidate
{
    protected $rule=[
        'code'=>'require|isNotEmpty'//参数

    ];
    protected  $message=[
        'code'=>"亲，不能获取信息的哦"

    ];
}