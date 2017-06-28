<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/6/1
 * Time: 15:43
 */

namespace app\common\repository;

use app\common\model\Category;
class CategoryRepository extends Category
{
    public static function getByPid($pid)
    {
        $data = self::where('pid',$pid)->order('sort','ASC')->select();
        return $data;
    }
}