<?php


namespace app\api\controller\v1;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;
use think\db\exception\BindParamException;
use think\Exception;

//use app\api\controller\BaseController;
//use app\api\model\Banner as BannerModel;
//use app\lib\exception\MissException;

/**
 * Banner资源
 */
class Banner
{


    /**
     * 获取Banner信息
     * @url     /banner/:id
     * @http    get
     * @param   int $id banner id
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public  function getBanner($id)
    {
        (new IDMustBePositiveInt())->goCheck();

          $banner=BannerModel::getBannerByID($id);


           if(!$banner){
               throw new BannerMissException();
           }
      $c=config('setting.img_prefix');//读取配置图片
      return $banner;

    }
}
