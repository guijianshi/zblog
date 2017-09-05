<?php
/**
 * Created by PhpStorm.
 * User: lyy15
 * Date: 2017/4/30
 * Time: 20:20
 */

namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\common\defined\exception\ObjectNotFoundException;
use app\common\defined\exception\ParameterException;
use app\common\model\ArticleTag;
use app\common\util\Ancestor;
use think\Db;
use think\Exception;
use think\Request;
use app\common\model\Article as ArticleModel;

class Article extends AdminBase
{
    private $method;

    /**
     * 获得文章列表
     * @param Request $request
     * @return \think\response\Json
     */
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

    /**
     * 新增文章
     * @param Request $request
     * @return \think\response\Json
     */
    public function add(Request $request)
    {
        $this->method = 'post';
        $data = $this->setArticleInfo($request);
        $article = new ArticleModel();


        return $this->flushDb($article, $request, $data);
    }

    /**
     * 删除文章
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        $article = model('article')::get($id);
        if (!$article)
            throw new ObjectNotFoundException('文章不存在');
        $ret = $article->delete();
        return $ret ? $this->suc('删除成功') : $this->err('删除失败');
    }

    /**
     * 查看单篇文章
     * @param Request $request
     * @return \think\response\Json
     */
    public function show(Request $request)
    {
        $id = $request->get('id');
        if (empty($id))
            return $this->err('id不得为空');
        $article = model('article')->alias('p')
            ->join('category c', 'c.cid = p.cid')
            ->join('article_tag at', 'p.aid = at.aid')
            ->with('category,tags')
            ->find($id);
        if (!$article)
            return $this->err('文章不存在');
        return $this->suc(['data' => $article]);
    }

    /**
     * 编辑文章表
     * @param Request $request
     * @param $id
     * @return \think\response\Json
     */
    public function edit(Request $request, $id)
    {
        $this->method = 'put';
        /* @var ArticleModel $article */
        $id = (int)$id;
        $article = model('article')->find($id);
        if (!$article)
            throw new ObjectNotFoundException('文章不存在');
        $data = $this->setArticleInfo($request,['aid' => $id]);

        $articleModel = new ArticleModel();
        return $this->flushDb($articleModel, $request, $data, 'UPDATE');

    }

    /**
     * 根据分类查找文章
     * @param $cname
     * @return \think\response\Json
     */
    public function getByCatogory($cname)
    {
        $data = model('article')->alias('p')
            ->join('category c', 'c.cid = p.cid')
            ->join('article_tag at', 'p.aid = at.aid')
            ->where('c.cname', $cname)->with('category,tags')
            ->select();
        $total = model('article')->alias('p')
            ->join('category c', 'c.cid = p.cid')
            ->join('article_tag at', 'p.aid = at.aid')
            ->where('c.cname', $cname)->with('category,tags')
            ->count();
        $data = $this->dataProcessor($data);
        return $this->suc(['data' => $data, 'total' => $total]);
    }


    /**
     * 根据标签查找文章
     * @param $tname
     * @return \think\response\Json
     */
    public function getByTag($tname)
    {
        $data = model('article')->alias('p')
            ->join('category c', 'c.cid = p.cid')
            ->join('article_tag at', 'p.aid = at.aid')
            ->join('tag t', 't.tid = at.tid')
            ->where('t.tname', $tname)->with('category,tags')->select();
        $total = model('article')->alias('p')
            ->join('category c', 'c.cid = p.cid')
            ->join('article_tag at', 'p.aid = at.aid')
            ->join('tag t', 't.tid = at.tid')->where('t.tname', $tname)
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

    /**
     * 设置文章主表
     * @param Request $request
     * @param array $data
     * @return array
     * @throws Exception
     */
    private function setArticleInfo(Request $request, array $data = [])
    {

        $method = $this->method;
        $category = $request->$method('category');
        $category = json_decode($category, true);
        if (empty($category))
            throw new ParameterException('分类为必填项');
        $cid = (int)array_pop($category);
        if (!model('category')->find($cid))
            throw new ObjectNotFoundException('分类不存在');
        $data['cid'] = $cid;
        $data['title'] = $request->$method('title');
        $data['author'] = $request->$method('author');
        $data['content'] = $request->$method('content');
        $data['keywords'] = $request->$method('keywords');
        $data['description'] = $request->$method('description');
        $data['is_show'] = $request->$method('is_show');
        $data['is_top'] = $request->$method('is_top');
        $data['is_original'] = $request->$method('is_original');

        $validate = validate('Article');
        if (!$validate->check($data)) {
            throw new Exception($validate->getError());
        }
        return $data;
    }

    /**
     * 设置关联
     * @param ArticleModel $article
     * @param Request $request
     */
    private function setTagsArtcileAssoc(ArticleModel $article, Request $request)
    {
        $method = $this->method;
        $article_tag = $request->$method('article_tag');
        $article_tag = json_decode($article_tag, true);
        if (!empty($article_tag)) {
            if ($this->method == 'post') {
                $article->tags()->saveAll($article_tag);
            } else {
                db('article_tag')->where('aid', $article->aid)->delete();
                $article->tags()->attach(array_column($article_tag, 'tid'));
            }
        }

    }

    /**
     * 更新数据
     * @param ArticleModel $article
     * @param Request $request
     * @param array $data
     * @param string $type
     * @return \think\response\Json
     * @throws Exception
     */
    private function flushDb(ArticleModel $article,Request $request, array $data, $type = 'INSERT')
    {
        try {
            Db::startTrans();
            if ($type == 'INSERT') {
                $ret = $article->allowField(true)->save($data);
            } else {
                $ret = $article->allowField(true)->save($data, ['aid' => $data['aid']]);
            }

            if ($ret) $this->setTagsArtcileAssoc($article, $request);//设置中间表

            Db::commit();
            if ($type == 'INSERT') {
                return $ret ? $this->suc(['suc_count' => $ret, 'msg' => "第{$article->aid}篇文章添加成功"]) : $this->err('添加失败');
            } else {
                return $ret ? $this->suc(['suc_count' => $ret, 'msg' => "第{$article->aid}篇文章修改成功"]) : $this->err('修改失败');
            }
        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }
}