<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/8/11
 * Time: 11:04
 */

namespace app\index\controller;


use app\common\controller\IndexBase;
use think\Request;

class Article extends IndexBase
{
    public function show($aid)
    {
        $article = model('article');
        $article = $article->with('category,tags')->find($aid);
        if (!$article)
            return $this->err('文章不存在');
        $article->setInc('click');
        $data = $this->dataProcessor([$article]);
        return $this->suc(['data' => $data[0],]);
    }

    public function searchByTitle(Request $request)
    {
        $key = $request->get('key');
        list($size, $offset) = $this->getRequest($request);
        $model = model('article')->with('category,tags')
            ->where('title', 'like', "%$key%");
        list($total, $data) = $this->getPage($model, $offset, $size);
        if (!$total)
            return $this->suc('没有符合内容的文章');
        $data = $this->dataProcessor($data);
        return $this->suc(['total' => $total, 'data' => $data,]);
    }
}