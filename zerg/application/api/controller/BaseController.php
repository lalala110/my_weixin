<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29
 * Time: 1:40
 */

namespace app\api\controller;


use think\Controller;
use app\api\service\Token as TokenService;
class BaseController extends  Controller
{
    protected  function  checkPrimaryScope(){

        TokenService::needPrimaryScope();
    }
    protected  function  checkExclusiveScope(){

        TokenService::needExclusiveScope();
    }


}