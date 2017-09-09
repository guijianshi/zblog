<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/9/7
 * Time: 14:11
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\util\FileUpload;
use think\Request;

class Upload extends AdminBase
{
    public function uploadImg(Request $request)
    {
        $fileUpload = new FileUpload();
        $img = $fileUpload->uploadImg($request);
        return $this->suc($img);
    }

    public function siteImg(Request $request)
    {
        $fileUpload = new FileUpload('/upload/', 'site-img');
        $img = $fileUpload->uploadImg($request);
        return $this->suc($img);
    }
}