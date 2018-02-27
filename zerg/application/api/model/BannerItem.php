<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30
 * Time: 21:45
 */

namespace app\api\model;

use think\Model;
class BannerItem extends  BaseModel
{
    protected  $hidden=['id','img_id','banner_id','update_time','delete_time'];//隐藏字段
   public function  img(){
       return $this->belongsTo('Image','img_id','id');//关联image 模型；
   }
}
