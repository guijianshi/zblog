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
//        $data = $article->alias('a')->join('category c','c.cid = a.cid','left')
//            ->column('a.aid,a.title,a.author,a.is_show,is_original,a.click,a.create_at,c.cname');
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
}
