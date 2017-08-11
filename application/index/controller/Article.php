<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/8/11
 * Time: 11:04
 */

namespace app\index\controller;


use app\common\controller\IndexBase;

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
}