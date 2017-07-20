<?php
namespace app\index\controller;

use app\common\controller\IndexBase;
use app\common\model\Article;
use think\Controller;
use think\Request;

class Index extends IndexBase
{
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $size = $request->get('size', 15);
        $offset = ($page - 1) * $size;
        $article = model('article');
        $data = $article->limit($offset, $size)->select();
        $total = $article->count();
        foreach ($data as $key => $article) {
            $data[$key]->cname = $article->category->cname;
            $data[$key]->key = $key;
            $tags = json_decode(json_encode($article->tags),true);
            $tags = array_column($tags,'tname');
            $data[$key]->tag = $tags;
            unset($article->tags);
        }
        return $this->suc(['data' => $data, 'total' => $total]);
    }

    public function category(Request $request)
    {
        $page = $request->get('page', 1);
        $size = $request->get('size', 15);
        $offset = ($page - 1) * $size;
        $p_cname = $request->get('p_cname');
        $pid = model('category')->where('cname', $p_cname)->limit(1)->select();
        if (empty($pid)) {
            return $this->err('标签不存在');
        }
        $pid = $pid[0]->cid;
        $categorys = db('category')->order('pid','ASC')->column(['cid value', 'cname label', 'pid']);
        $c = $this->getSubs($categorys,$pid);
        $cname = $request->get('cname');
        $article = model('article');

        $data = $article->alias('p')->join('category c','p.cid = c.cid','inner')
            ->whereIn('p.cid',$this->getSubCid($categorys, $pid, [$pid]))->limit($offset, $size)->select();
        $total = $article->count();
        foreach ($data as $key => $article) {
            $data[$key]->cname = $article->category->cname;
            $data[$key]->key = $key;
            $tags = json_decode(json_encode($article->tags),true);
            $tags = array_column($tags,'tname');
            $data[$key]->tag = $tags;
            unset($article->tags);
        }
        return $this->suc(['data' => $data, 'total' => $total, 'cname' => $c]);
    }

    public function getSubs($categorys, $pid = 0, $level = 1)
    {
        $subs = array();
        $k = 0;
        foreach ($categorys as $category) {
            if ($category['pid'] == $pid) {
                $subs[$k] = ['value' => $category['value'], 'label' => $category['label'], 'level' => $level];
                $subs[$k]['child'] = $this->getSubs($categorys, $category['value'], $level + 1);
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
