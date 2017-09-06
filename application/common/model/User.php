<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/8/30
 * Time: 13:35
 */

namespace app\common\model;


class User extends Base
{
    public $pk = 'id';
    protected $table = 'lin_oauth_user';

    protected $type = [
        'create_time' => 'timestamp:Y/m/d H:i:s',
        'update_time' => 'timestamp:Y/m/d H:i:s',
        'last_login_time' => 'timestamp:Y/m/d H:i:s',
    ];

    /*时间戳字段名*/
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    /*时间戳自动写入*/
    protected $autoWriteTimestamp = true;

    protected function setIpAttr()
    {
        return request()->ip();
    }

    protected function setGenderAttr($value)
    {
        switch ($value[0]) {
            case '未':
                return 0;
                break;
            case '男':
                return 1;
                break;
            case '女':
                return 2;
                break;
            default:
                return 0;
                break;
        }
    }

    protected function setTypeAttr($value)
    {
        switch (strtolower($value)) {
            case 'qq':
                return 1;
                break;
            case 'weibo':
                return 2;
                break;
            case 'taobao':
                return 3;
                break;
            default:
                return 0;
                break;
        }
    }

    protected function getTypeAttr($value)
    {
        switch ($value) {
            case 0:
                return 'local';
                break;
            case 1:
                return 'qq';
                break;
            case 2:
                return 'weibo';
                break;
            case 3:
                return 'taobao';
                break;
            default:
                return 'local';
                break;
        }
    }
}