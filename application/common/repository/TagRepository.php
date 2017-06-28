<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/6/2
 * Time: 9:34
 */

namespace app\common\repository;

use app\common\model\Tag;
class TagRepository extends Tag
{
    public static function getTags()
    {
        $tags = self::field('tid,tname')->limit(20)->select();
        return $tags;
    }

    public static function getTagByTname($tname)
    {
        $tags = self::field('tid,tname')->where('tname',$tname)->find();
        return $tags;
    }
}