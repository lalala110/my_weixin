<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/2
 * Time: 16:22
 */

namespace app\api\model;
use think\Model;
use app\api\model\Product as ProductModel;
class Product extends  BaseModel
{
    protected $hidden = [
        'delete_time', 'main_img_id', 'pivot', 'from', 'category_id',
        'create_time', 'update_time'];
    public function getMainImgUrlAttr($value,$data)
    {
        return $this->prefixImgUrl($value, $data);
    }
    public function imgs(){
        return $this->hasMany('ProductImage','product_id','id');
    }
    public function properties(){
        return $this->hasMany('ProductProperty','product_id','id');
    }
//指定数量倒叙排列
    public static function getMostRecent($count){
        $products=self::limit($count)->order('create_time desc')->select();
        return $products;
    }

//编写查询方法
    public static function getProductsByCategoryID(
        $categoryID, $paginate = true, $page = 1, $size = 30)
    {
        $query = self::
        where('category_id', '=', $categoryID);
        if (!$paginate)
        {
            return $query->select();
        }
        else
        {
            // paginate 第二参数true表示采用简洁模式，简洁模式不需要查询记录总数
            return $query->paginate(
                $size, true, [
                'page' => $page
            ]);
        }
    }

    public static function getProductDetail($id){
//通过链式方法进行升序排列
        $product=self::with(['imgs'=>function($query){
            $query->with(['imgUrl'])->order('order','asc');
        }

        ])
            ->with(['properties'])
            ->find($id);
        return $product;
    }


}