<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/2
 * Time: 16:21
 */

namespace app\api\controller\v1;


use app\api\validate\IDCollection;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ThemeException;
use think\Controller;
use app\api\model\Theme as ThemeModel;
class Theme extends Controller
{
    /**
     * @url /theme ids=id1,id2,id3...
     * return theme model
     *
     */
   public function getSimpleList($ids=''){
       $validate = new IDCollection();
       $validate->goCheck();
       $ids = explode(',', $ids);
       $result = ThemeModel::with('topicImg,headImg')->select($ids);
//        $result = ThemeModel::getThemeList($ids);
       if ($result->isEmpty()) {
           throw new ThemeException();
       }
       return $result;
   }

    /**
     * @param $id
     * url /theme/:id
     */
   public function getComplexOne($id){
       (new IDMustBePositiveInt())->goCheck();
       $theme=ThemeModel::getThemeWithProducts($id);
       if(!$theme){
           throw new ThemeException();
       }
       return $theme;
   }
}