<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/8/7
 * Time: 9:54
 */

namespace app\common\validate;


use think\Validate;

class Comment extends Validate
{
    protected $rule = [
        'content' => 'require',
        'aid' => 'number',
        'auid' => 'number',
        'pid' => 'number',
    ];

    protected $message = [
        'content.require' => '评论必须',
        'aid.number' => '文章id必须是数字',
        'auid.number' => '用户id必须是数字',
        'pid.number' => '父级评论id必须是数字',
    ];
}