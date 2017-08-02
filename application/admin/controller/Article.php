<?php
/**
 * Created by PhpStorm.
 * User: lyy15
 * Date: 2017/4/30
 * Time: 20:20
 */

namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Db;
use think\Exception;
use think\Request;

class Article extends AdminBase
{
    public function get(Request $request)
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

    public function add(Request $request)
    {

        $data['title'] = $request->post('title');
//        $d = $request->post('category');
//        return $this->suc(gettype($d));
        $category = $request->post('category');
        $category = json_decode($category,true);
        $data['cid'] = array_pop($category);
        $data['author'] = $request->post('author');
        $data['content'] = $request->post('content');
        $data['keywords'] = $request->post('keywords');
        $data['description'] = $request->post('description');
        $data['is_show'] = $request->post('is_show');
        $data['is_top'] = $request->post('is_top');
        $data['is_original'] = $request->post('is_original');
        $article_tag = $request->post('article_tag');
        $article_tag = json_decode($article_tag,true);
        $validate = validate('Article');
        if (!$validate->check($data)) {
            return $this->err($validate->getError());
        }
        $article = model('article');
        Db::startTrans();
        try {
            $ret = $article->allowField(true)->save($data);
            if (empty($article_tag))
                $article->tags()->saveAll($article_tag);
            Db::commit();
            return $ret ? $this->suc(['suc_count' => $ret, 'msg' => "第{$article->aid}篇文章添加成功"]) : $this->err('添加失败');
        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        $article = model('article')::get($id);
        if (!$article)
            return $this->err('文章不存在');
        $ret = $article->delete();
        return $ret ? $this->suc('删除成功') : $this->err('删除失败');
    }

    public function show(Request $request)
    {
        $id = $request->get('id');
        if (is_null($id))
            return $this->err('id不得为空');
        $article = model('article')->find($id);
        if (!$article)
            return $this->err('文章不存在');
        $article->tags;
        return $this->suc(['data' => $article]);
    }

    public function edit(Request $request, $id)
    {
        $article = model('article')->find($id);
        if (!$article)
            return $this->err('文章不存在');
        if ($request->isGet()) {
            $article_tags = $article->tags;
            $tags = [];
            foreach ($article_tags as $tag) {
                $a = $tag->pivot->tid;
                $tags[] = $a;
            }
            $article->article_tags = $tags;
            return view('', ['article' => $article]);
        } else {
            $post = $request->post();
            $data = $post['form'];
            $article_tag = [];
            $article_tags = $post['article_tags'];
            foreach ($article_tags as $tid) {
                $article_tag[] = $tid;

            }
            Db::startTrans();
            try {
                $ret = $article->allowField(true)->save($data);
                if (!empty($article_tag))
                    $article->tags()->attach($article_tag);
                Db::commit();
                return $ret ? $this->suc('编辑成功') : $this->err('编辑失败');

            } catch (Exception $e) {
                Db::rollback();
                throw $e;
            }
        }

    }
}