<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/5
 * Time: 0:31
 */

namespace app\api\model;
use think\Model;

class User extends BaseModel
{
//    public  function address(){
//        return $this->hasOne('UserAddress','user_id','id');
//    }
//    public static  function getByOpenID($openid){
//        $user=self::where('openid','=',$openid)->find();
//        return $user;
//    }
    protected $autoWriteTimestamp = true;
//    protected $createTime = ;

    public function orders()
    {
        return $this->hasMany('Order', 'user_id', 'id');
    }

    public function address()
    {
        return $this->hasOne('UserAddress', 'user_id', 'id');
    }

    /**
     * 用户是否存在
     * 存在返回uid，不存在返回0
     */
    public static function getByOpenID($openid)
    {
        $user = User::where('openid', '=', $openid)
            ->find();
        return $user;
    }

}