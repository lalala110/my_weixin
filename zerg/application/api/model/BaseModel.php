<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/2
 * Time: 15:49
 */

namespace app\api\model;
use think\Model;

class BaseModel extends Model//所有模型继承BaseModel
{
    protected function prefixImgUrl($value,$data)//读取器
    {
        $finalUrl=$value;//定义接收最后图片的路径值，当为1表示本地的图片，为2表示网络上的图片
        if($data['from']==1) {
            return config('setting.img_prefix') . $value;//拼接图片路径
        }
        return $finalUrl;
    }
}