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

    protected $type = [
        'create_time'=>'timestamp:Y/m/d H:i:s',
        'update_time'=>'timestamp:Y/m/d H:i:s',
    ];
    /*时间戳字段名*/
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    /*时间戳自动写入*/
    protected $autoWriteTimestamp = true;

    public function getStatusAttr($value)
    {
        $status = [-1 => '删除', 0 => '禁用', 1 => '正常', 2 => '待审核'];
        return $status[$value];
    }

    public function article()
    {
        return $this->belongsTo('article', 'aid', 'aid');
    }

    public function user()
    {
        return $this->belongsTo('user', 'uid', 'uid');
    }
}