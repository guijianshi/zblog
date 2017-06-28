<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/4/20
 * Time: 17:02
 */

namespace app\common\model;

use app\common\model\Base;
use traits\model\SoftDelete;
class Article extends Base
{
    protected function initialize()
    {
        parent::initialize();
    }
    public $pk = 'aid';
    protected $table = 'lin_article';
    protected $type = [
        'create_at'=>'timestamp:Y/m/d H:i:s',
        'update_at'=>'timestamp:Y/m/d H:i:s',
    ];
    use SoftDelete;
    protected $deleteTime = 'delete_at';
    /*时间戳字段名*/
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';
    /*时间戳自动写入*/
    protected $autoWriteTimestamp = true;

    protected $readonly = ['cid','author'];

    /**
     * 分类关联
     * @return $this
     */
    public function category()
    {
        return $this->belongsTo('\\app\\common\\model\\Category','cid')->field('cname');
    }

    /**
     * 标签关联
     * @return \think\model\relation\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('\\app\\common\\model\\Tag','lin_article_tag','tid','aid');
    }
}