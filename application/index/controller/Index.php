<?php

namespace app\index\controller;

use app\common\controller\IndexBase;
use app\common\defined\exception\FileNotFoundException;
use app\common\defined\exception\ObjectNotFoundException;
use think\Request;

class Index extends IndexBase
{
    public function indexA(Request $request)
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
        $categorys = model('category')->column('cid value, cname label, pid, icon');
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
                $subs[$k] = [
                    'value' => $category['value'],
                    'label' => $category['label'],
                    'icon' => $category['icon'],
                    'level' => $level,
                ];
                $subs[$k]['child'] = $this->getSubs($categorys, $category['value'], $level + 1);
                if (empty($subs[$k]['child']))
                    unset($subs[$k]['child']);
                $k++;
            }
        }
        return $subs;
    }

    /**
     * 获取子分类
     * @param array $categorys
     * @param int $pid 父级分类id
     * @param $cids
     * @return array
     */
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

    public function index()
    {
        return view();
    }


    public function setting()
    {
        $filename = APP_PATH . '../public/config/site_info.txt';
        if (!file_exists($filename))
            throw new FileNotFoundException();
        $data = file_get_contents($filename);
        if (empty($data))
            throw new ObjectNotFoundException();
        return $this->suc(['data' => $data]);


    }
}
