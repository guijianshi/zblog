<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/8/14
 * Time: 15:41
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;

class Icon extends AdminBase
{
    public function get()
    {
        $data = db('icon')->select();
        return $this->suc(['data' => $data]);
    }
}