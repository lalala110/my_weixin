<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/2
 * Time: 18:37
 */

namespace app\api\validate;


class Count extends BaseValidate
{
  protected $rule=[
      'count'=>'isPositiveInteger|between:1,15'
  ];
}