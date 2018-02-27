<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/2
 * Time: 13:57
 */

namespace app\api\model;
use think\Model;

class Image extends  BaseModel
{
    protected  $hidden=['id','from','update_time','delete_time'];
   public function getUrlAttr($value,$data)//读取器
   {
    return $this->prefixImgUrl($value, $data);//目的不让所有继承baseModel类
   }
}