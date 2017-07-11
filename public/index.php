<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
header("Access-Control-Allow-Origin:*");

header('Access-Control-Allow-Methods:DELETE, GET, POST, PUT');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');
if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
    exit;
}
define('APP_PATH', __DIR__ . '/../application/');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';

