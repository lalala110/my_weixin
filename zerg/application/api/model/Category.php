<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/2
 * Time: 19:22
 */

namespace app\api\model;


class Category extends  BaseModel
{
    protected  $hidden=['update_time','delete_time','create_time'];
   public  function  img(){
       return $this->belongsTo('Image',"topic_img_id","id");
   }
}