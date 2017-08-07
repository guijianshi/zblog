<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/6/28
 * Time: 14:44
 */

namespace app\common\model;


use think\Model;
use traits\model\SoftDelete;

class Comment extends Model
{
    protected function initialize()
    {
        parent::initialize();
    }

    public $pk = 'cmid';
    protected $table = 'lin_comment';

    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function getStatusAttr($value)
    {
        $status = [-1 => '删除', 0 => '禁用', 1 => '正常', 2 => '待审核'];
        return $status[$value];
    }
}