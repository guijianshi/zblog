<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/6/27
 * Time: 8:23
 */

namespace app\admin\controller;


use think\Controller;

class Test extends Controller
{
    public function test1()
    {
        $admin = \controller('app\admin\controller\Admin');
        if ($admin->is_admin('lin1', 12346))
            return 02;
        return 12;
    }

    public function test2()
    {
        return session('test');
    }
}