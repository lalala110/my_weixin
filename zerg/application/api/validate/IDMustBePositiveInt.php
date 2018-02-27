<?php

namespace app\api\validate;
use think\Validate;
class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',

    ];
   protected $message=[
       'id'=>"必须是以正整数的形式"
   ];

}
