<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/8/4
 * Time: 9:59
 */

namespace app\common\util;


class Ancestor
{
    public $assoc;

    public $key_id;

    public $data;

    public function __construct($assoc, $data)
    {
        $this->assoc = $assoc;
        $this->key_id = array_flip(array_column($assoc, 'value'));//array_flip 交换数组当中的值和键
        $this->data = $data;
    }

    public static function getSub($locations, $pid = 0,$key = 'cid')
    {
        $data = [];
        foreach ($locations as $k => $location) {
            if ($location['pid'] == $pid) {
                unset($locations[$k]);
                $children = self::getSub($locations, $location[$key], $key);
                if (!empty($children)) $location['children'] = $children;
                $data[] = $location;
            }
        }
        return $data;
    }

    public function getAncestor()
    {
        if (isset($this->data['cid']))
        {
            $this->data['ancestor'] = $this->getPid($this->data['cid']);
            return $this->data;
        }
        foreach ($this->data as $key => $item)
        {
            $this->data[$key]['ancestor'] = $this->getPid($item['cid']);
        }
        return $this->data;
    }

    public function getPid($id)
    {
        $pid = $this->assoc[$this->key_id[$id]];
        if (0 != $pid['pid'])
            return $ancestor = array_merge($this->getPid($pid['pid']),[$id]);
        return $ancestor = [$id];
    }
}