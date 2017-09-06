<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/9/5
 * Time: 17:29
 */

namespace app\common\dao;


use app\common\model\User;

class UserDao extends User
{
    public function add($data)
    {
        $this->data($data);
        $this->save();
        return $this;
    }
}