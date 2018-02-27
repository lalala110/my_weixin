<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/2
 * Time: 18:34
 */

namespace app\api\controller\v1;

use app\api\model\Product as ProductModel;
use app\api\validate\Count;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\Productexception;
use think\Exception;
class Product
{
    public function getRecent($count=20){
        (new Count())->goCheck();
        $products=ProductModel::getMostRecent($count);
        if($products->isEmpty()){
            throw new Productexception();
        }

        $products=$products->hidden(['summary']);//临时隐藏summary字段collection数据集
        return $products;}

        public function getAllInCatgory($id){
            (new IDMustBePositiveInt())->goCheck();
            $products=ProductModel::getProductsByCategoryID($id);
            if($products->isEmpty()){
                throw new Productexception();
            }
            $products=$products->hidden(['summary']);//临时隐藏summary字段
            return $products;
        }
        public function getOne($id)
        {
            (new IDMustBePositiveInt())->goCheck();
            $product = ProductModel::getProductDetail($id);
            if (!$product) {
                throw new Productexception();
            }
            return $product;
        }
        public function deleteOne($id){
            //不是所有人都可以调用,权限的控制

        }
}