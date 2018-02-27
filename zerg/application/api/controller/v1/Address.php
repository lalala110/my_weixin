<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/10
 * Time: 22:54
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\User;
use app\api\model\UserAddress;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\SuccessMessage;
use app\lib\exception\TokenException;
use app\lib\exception\UserException;
use think\Controller;

class Address extends  BaseController
{
    protected $beforeActionList=[
        'checkPrimaryScope'=>['only'=>'createOrUpdateAddress,getUserAddress']

    ];//前置操作
    /**
     * 获取用户地址信息
     * @return UserAddress
     * @throws UserException
     */
    public function getUserAddress(){
        $uid = TokenService::getCurrentUid();
        $userAddress = UserAddress::where('user_id', $uid)
            ->find();
        if(!$userAddress){
            throw new UserException([
                'msg' => '用户地址不存在',
                'errorCode' => 60001
            ]);
        }
        return $userAddress;
    }


    public function createOrUpdateAddress(){
// 编写创建和更新地址接口管理员和普通的用户都可以访问接口>=16,
       $validate=new AddressNew();
       $validate->goCheck();
       (new AddressNew())->goCheck();//验证器通过
       //根据TOKEn 获取UID，来查找数据，判断是否存在，不存在抛出异常
       //获取客户端提交的信息，
       //判断地址是否存在
           $uid=TokenService::getCurrentUid();//简单调用获取UID
          $user=UserModel::get($uid);
          if(!$user)
          {throw new UserException();}
          $dataArray=$validate->getDataByRule(input('post.'));//post数据说明

          $userAddress=$user->address;//通过模型关联获取用户的地址
         if(!$userAddress){
          //模型关联模型新增一条属性
             $user->address()->save($dataArray);}
             else{
                 $user->address->save($dataArray);
             }
             return json(new SuccessMessage(),201);//返回模型
       }

}