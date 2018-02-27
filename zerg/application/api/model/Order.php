<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29
 * Time: 22:15
 */

namespace app\api\model;


class Order extends BaseModel
{
  //设置隐藏字段
     protected $hidden=['user_id','delete_time','update_time'];
    //自动写入时间戳
    protected $autoWriteTimestamp=true;
}