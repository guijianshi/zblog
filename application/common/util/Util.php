<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/4/25
 * Time: 16:18
 */

namespace app\common\util;


class Util
{
    /**
     * 去除数组内value 两段空格,去除value 值为null
     * @param array $arr
     */
    public static function arr_trim_unset_null(array $arr)
    {
        foreach($arr as $key=>$value){
            if(trim($value) === '')
                unset($arr[$key]);
            else
                $arr[$key] = trim($value);
        }
        return $arr;
    }
}