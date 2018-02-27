<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 0:53
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
    protected $hidden = [
        'delete_time', 'product_img_id', 'id' ];
}