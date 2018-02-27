<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/2
 * Time: 19:21
 */

namespace app\api\controller\v1;
use app\api\model\Category as CategoryModel;
use app\lib\exception\CatgoryException;

class Category
{
    public function getAllCategories()
    {
        $categories = CategoryModel::all([], 'img');
        if(empty($categories)){
            throw new CatgoryException([
                'msg' => '还没有任何类目',
                'errorCode' => 50000
            ]);
        }
        return $categories;
    }

}