<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/11
 * Time: 0:21
 */

namespace app\api\model;


class UserAddress extends  BaseModel
{
   protected $hidden=['id','delete_time','user_id'];
}