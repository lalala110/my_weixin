<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 15:48
 */

namespace app\api\validate;

use think\Validate;
class TestValidate extends Validate
{
    protected $rule=[
        'name'=>'requrie|max:10',
        'email'=>'email'
    ];
}