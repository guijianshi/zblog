<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/6/22
 * Time: 16:12
 */

namespace app\common\validate;


use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'title' => 'require|min:5|max:100',
        'author' => 'require|max:20' ,
        'content' => 'require',
        'keywords' => 'require|max:255',
        'description' => 'require|max:255',
        'is_show' => 'require|in:0,1',
        'is_top' => 'require|in:0,1',
        'is_original' => 'require|in:0,1',

    ];

    protected $msg = [
        'title.require' => '标题不得为空',
        'title.max' => '标题不得长于100字符',
        'author.require' => '年龄必须是数字',
        'is_show.in' => '年龄只能在1-120之间',
        'content' => '内容不得为空',
    ];
}