<?php


namespace app\api\controller\v2;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;
use think\db\exception\BindParamException;
use think\Exception;



/**
 * Banner资源
 */
class Banner
{


    public function getBanner($id)
    {


        return "v2 Version";

    }
}
