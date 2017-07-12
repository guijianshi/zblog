<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/5/22
 * Time: 14:52
 */

namespace app\common\controller;

use think\Controller;
class Base extends Controller
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function suc($msg)
    {
        if(is_array($msg)){
            $result =  array_merge(['ret'=>1],$msg);
        }elseif(is_string($msg)){
            $result =  ['ret'=>1,'msg'=>$msg];
        }else{
            $result = ['ret'=>1,'msg'=>'操作成功'];
        }
        return json($result);
    }
    public function err($msg)
    {
        if(is_array($msg)){
            $result =  array_merge(['ret'=>0],$msg);
        }elseif(is_string($msg)){
            $result =  ['ret'=>0,'msg'=>$msg];
        }else{
            $result = ['ret'=>0,'msg'=>'操作成功'];
        }
        return json($result);
    }
}