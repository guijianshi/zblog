<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/5/22
 * Time: 14:52
 */

namespace app\common\controller;

use think\Controller;
use think\db\Query;
use think\Request;

class Base extends Controller
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function suc($msg)
    {
        if(is_array($msg)){
            $result =  array_merge(['ret'=>1],$msg);
        }elseif(is_string($msg)){
            $result =  ['ret'=>1,'msg'=>$msg];
        }else{
            $result = ['ret'=>1,'msg'=>'操作成功'];
        }
        return json($result);
    }
    public function err($msg)
    {
        if(is_array($msg)){
            $result =  array_merge(['ret'=>0],$msg);
        }elseif(is_string($msg)){
            $result =  ['ret'=>0,'msg'=>$msg];
        }else{
            $result = ['ret'=>0,'msg'=>'操作成功'];
        }
        return json($result);
    }

    /**
     * @param $data
     */
    public function dataProcessor(array $data)
    {
        foreach ($data as $key => $article) {
            $data[$key]->cname = $article->category->cname;
            $data[$key]->key = $key;
            $tags = json_decode(json_encode($article->tags), true);
            $tags = array_column($tags, 'tname');
            $data[$key]->tag = $tags;
        }
        return $data;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getRequest(Request $request)
    {
        $page = $request->get('page', 1);
        $size = $request->get('size', 15);
        $offset = ($page - 1) * $size;
        return array($size, $offset);
    }

    /**
     * 获取页面总数和页面对应信息
     * @param Query $model
     * @param $offset
     * @param $size
     * @return array
     */
    public function getPage(Query $model, $offset, $size)
    {
        $data = $model->limit($offset, $size)
            ->select();
        $total = $model->count();
        return [$total, $data];
    }
}