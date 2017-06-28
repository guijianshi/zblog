<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/5/22
 * Time: 13:33
 */

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;

class Security extends Controller
{
    /**
     * 登入
     * @param Request $request
     * @return \think\response\Json
     */
    public function login(Request $request)
    {
        if (!$request->isPost())
            return json(['ret' => 0]);
//        if (!captcha_check(trim($request->post('verify'))))
//            return json(['ret' => 0, 'msg' => '验证码错误']);
        $username = trim($request->post('username'));
        $password = trim($request->post('password'));
        if (!$username || !$password)
            return json(['ret' => 0, '请输入用户名和密码']);
        $admin = db('admin')->where('username', $username)->find();
        if (!$admin)
            return json(['ret' => 0, '用户不存在']);
        if (!password_verify(md5($password), $admin['password']))
            return json(['ret' => 0, '密码不正确']);
        $unique = md5(md5(time() . 'guijianshi') . 'lin');
        if (!db('admin')->where('username', $username)->setField('unique', $unique))
            return json(['ret' => 0, '登入失败,请重试']);
        session('username', $username);
        session('is_admin', 1);
        session('unique', $unique);
        cookie('unique', $unique);
        cookie('username', $username);
        return json(['ret' => 1, 'msg' => '登入成功']);
    }

    public function logout()
    {
        session('username', null);
        session('is_admin', null);
        cookie('username', null);
        cookie('unique', null);
        if ($this->request->isAjax()) {
            return json(['ret' => 1, 'msg' => '退出成功']);
        } else {
            $this->success('退出成功', '/');
        }
    }

    public function logined_info()
    {
        $username = cookie('username');
        $unique = cookie('unique');
        if ($username && $unique) {
            $admin = \controller('app\admin\controller\Admin');
            if ($admin->is_admin($username, $unique)) {
                session('username', $username);
                session('is_admin', 1);
                session('unique', $unique);
                return json(['ret' => 1, 'msg' => '用户已登入', 'username' => session('username')]);
            } else
                return json(['ret' => 0, 'msg' => '未登入']);
        } else {
            return json(['ret' => 0, 'msg' => '未登入']);
        }
    }


}