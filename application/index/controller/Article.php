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
        $data = $article->with('category,tags')->where('aid',$aid)->select();
        $data = $this->dataProcessor($data);
        return $this->suc(['data' => $data,]);
    }
}