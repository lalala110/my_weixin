<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27
 * Time: 23:52
 */

namespace app\lib\exception;

use think\Config;
use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle//继承异常处理的类
{

    private $code;
    private $msg;
    private $errorCode;
   //需要返回客户端当前请求的URL的路径
    public function  render(\Exception $e){

      if($e instanceof BaseException){
          //如果是自定义的异常
          $this->code=$e->code;
          $this->msg=$e->msg;
          $this->errorCode=$e->errorCode;
      }
      else{
           Config::get('app_debug');
          if(config('app_debug')){
             return parent::render($e);//moren fangfa
          }
          else{
              $this->code=500;
              $this->msg='服务器内部出现了错误，请重试';
              $this->errorCode=999;
              $this->recordErrorLog($e);
          }
//         $this->code=500;
//         $this->msg='服务器内部出现了错误，请重试';
//         $this->errorCode=999;
//         $this->recordErrorLog($e);
      }
      $request=Request::instance();
      $result=[
          'msg'=>$this->msg,
          'error_code'=>$this->errorCode,
          'request_url'=>$request->url()
      ];
      return json($result,$this->code);
    }
    private function  recordErrorLog(\Exception $e){
        Log::init([
            'type'=>'File',
            'path'=>LOG_PATH,
            'level'=>['error']
        ]);
        Log::record($e->getMessage(),'error');
    }
}