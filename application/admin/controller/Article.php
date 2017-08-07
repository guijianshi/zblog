<?php
/**
 * Created by PhpStorm.
 * User: lyy15
 * Date: 2017/4/30
 * Time: 20:20
 */

namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\common\util\Ancestor;
use think\Db;
use think\Exception;
use think\Request;
use app\common\model\Article as ArticleModel;

class Article extends AdminBase
{
    public function get(Request $request)
    {
        $page = $request->get('page', 1);
        $size = $request->get('size', 15);
        $offset = ($page - 1) * $size;
        $article = model('article');
        $data = $article->with('category,tags')->limit($offset, $size)->select();
        $total = $article->count();
        $data = $this->dataProcessor($data);
        return $this->suc(['data' => $data, 'total' => $total]);
    }

    public function add(Request $request)
    {
        $data['title'] = $request->post('title');
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
        $article = new ArticleModel();
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
        $article = model('article')->alias('p')
            ->join('category c','c.cid = p.cid')
            ->join('article_tag at','p.aid = at.aid')
            ->with('category,tags')
            ->find($id);
        if (!$article)
            return $this->err('文章不存在');
        return $this->suc(['data' => $article]);
    }

    public function edit(Request $request, $id)
    {
        $article = model('article')->find($id);
        if (!$article)
            return $this->err('文章不存在');
        $data['title'] = $request->post('title');
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

    public function getByCatogory($cname)
    {
        $data = model('article')->alias('p')
            ->join('category c','c.cid = p.cid')
            ->join('article_tag at','p.aid = at.aid')
            ->where('c.cname',$cname)->with('category,tags')
            ->select();
        $total = model('article')->alias('p')
            ->join('category c','c.cid = p.cid')
            ->join('article_tag at','p.aid = at.aid')
            ->where('c.cname',$cname)->with('category,tags')
            ->count();
        $data = $this->dataProcessor($data);
        return $this->suc(['data' => $data, 'total' => $total]);
    }


    public function getByTag($tname)
    {
        $data = model('article')->alias('p')
            ->join('category c','c.cid = p.cid')
            ->join('article_tag at','p.aid = at.aid')
            ->join('tag t', 't.tid = at.tid')
            ->where('t.tname',$tname)->with('category,tags')->select();
        $total = model('article')->alias('p')
            ->join('category c','c.cid = p.cid')
            ->join('article_tag at','p.aid = at.aid')
            ->join('tag t', 't.tid = at.tid')->where('t.tname',$tname)
            ->with('tags')->count();
        $data = $this->dataProcessor($data);
        return $this->suc(['data' => $data, 'total' => $total]);
    }

    public function getAncestorId($data)
    {
        $assoc = ArticleModel::all();

        $helper = new Ancestor($assoc, $data);
        $data = $helper->getAncestor();
        return $data;
    }
}