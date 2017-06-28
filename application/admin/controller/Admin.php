<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/6/27
 * Time: 8:44
 */

namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Controller;

class Admin extends Controller
{
    public function is_admin($username, $unique)
    {
        $admin = db('admin')->where('username', $username)->where('unique', $unique)->select();
        if ($admin) {
            return true;
        } else {
            return false;
        }
    }
}