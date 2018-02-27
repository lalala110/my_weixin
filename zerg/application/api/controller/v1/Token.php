<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/5
 * Time: 0:17
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenService;
class Token
{
  public function getToken($code='')//传入code
  {
      (new TokenGet())->goCheck();
         $ut=new UserToken($code);
        $token=$ut->get();
        return [
            'token'=>$token
        ];

  }
  public function verifyToken($token=''){
      if(!$token){
          throw new ParameterException([
             'token 不允许是空的'
          ]);
      }
      $valid=TokenService::verifyToken($token);
      return [
          'isValid'=>$valid
      ];
  }
}