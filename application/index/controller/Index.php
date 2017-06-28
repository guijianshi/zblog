<?php
namespace app\index\controller;

use think\Controller;
use think\Request;

class Index extends Controller
{
    public function index(Request $request)
    {
        switch ($request->url()){
            case '/v1/logined_info':
                return $this->redirect('admin/security/logined_info');
                break;
            case '/v1/logined_info.html':
                return $this->redirect('admin/security/logined_info');
                break;
            default:
                break;
        }

    }
}
