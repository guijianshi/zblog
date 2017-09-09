<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/9/7
 * Time: 15:44
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\defined\exception\UploadException;
use think\Request;

class Index extends AdminBase
{
    public function index()
    {
        return view();
    }

    public function setting(Request $request)
    {
        $filename = APP_PATH . '../public/config/site_info.txt';

        $data = $request->get('data');
        if (file_exists($filename))
            @unlink($filename);
        $ret = file_put_contents($filename, $data);
        if (!$ret)
            throw new UploadException();
        else
            return $this->suc(['data' => json_decode($data)]);
    }
}