<?php

namespace app\index\controller;

use app\common\controller\IndexBase;
use think\Request;

class Index extends IndexBase
{
    public function index(Request $request)
    {
        list($size, $offset) = $this->getRequest($request);
        $article = model('article');
        $data = $article->limit($offset, $size)->select();
        $total = $article->count();
        $data = $this->dataProcessor($data);
        return $this->suc(['data' => $data, 'total' => $total]);
    }

    public function getByCatogory(Request $request, $cname)
    {
        list($size, $offset) = $this->getRequest($request);
        $model = model('article')->alias('p')
            ->join('category c', 'c.cid = p.cid')
            ->where('c.cname', $cname);

        list($total, $data) = $this->getPage($model, $offset, $size);
        $data = $this->dataProcessor($data);
        $cid = model('category')->where('cname', $cname)->column('cid');
        $categorys = model('category')->column('cid value, cname label, pid');
        $childrens = $this->getSubs($categorys, $cid[0]);
        return $this->suc(['data' => $data, 'total' => $total, 'child' =>$childrens]);
    }

    public function getByTag(Request $request, $tname)
    {
        list($size, $offset) = $this->getRequest($request);
        $model = model('article')->alias('p')
            ->join('category c', 'c.cid = p.cid')
            ->join('article_tag at', 'p.aid = at.aid')
            ->join('tag t', 't.tid = at.tid')
            ->where('t.tname', $tname);
        list($total, $data) = $this->getPage($model, $offset, $size);
        $data = $this->dataProcessor($data);
        return $this->suc(['data' => $data, 'total' => $total]);
    }

    public function getSubs($categorys, $pid = 0, $level = 1)
    {
        $subs = array();
        $k = 0;
        foreach ($categorys as $key => $category) {
            $category = is_object($category) ? $category->toArray() : $category;
            if ($category['pid'] == $pid) {
                unset($categorys[$key]);
                $subs[$k] = ['value' => $category['value'], 'label' => $category['label'], 'level' => $level];
                $subs[$k]['child'] = $this->getSubs($categorys, $category['value'], $level + 1);
                if (empty($subs[$k]['child']))
                    unset($subs[$k]['child']);
                $k++;
            }
        }
        return $subs;
    }

    public function getSubCid($categorys, $pid, $cids)
    {
        foreach ($categorys as $category) {
            if ($category['pid'] == $pid) {
                $cids[] = $category['value'];
                $cids = array_merge($cids, $this->getSubCid($categorys, $category['value'], []));
            }
        }
        return $cids;
    }
}
