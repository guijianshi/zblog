<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/4/25
 * Time: 16:05
 */

namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\common\util\Util;
use think\Db;
use think\Request;

class Tag extends AdminBase
{
    public function add(Request $request)
    {
        $tags = $request->post('tname');
        if(!$tags)
            return $this->err('标签内容为空');
        $tags = str_replace('，',',',$tags);
        $tags = explode(',',$tags);
        $tags = Util::arr_trim_unset_null($tags);

        $exist_tag = db('tag')->column('tname');
        $tags = array_diff($tags,$exist_tag);
        foreach($tags as $tag){
            $data[] = ['tname'=>$tag];
        }
        if(empty($data))
            return $this->err('标签已存在');
        $suc_count =  db('tag')->insertAll($data);
        return $this->suc(['suc_count'=>$suc_count]);
    }

    public function get()
    {
        $id = request()->get('id',0);
        if($id == 0)
            $data =  db('tag')->field(['tid' => 'key', 'tid', 'tname'])->select();
        else
            $data = db('tag')->find($id);
        return $this->suc(['data'=>$data]);
    }


    public function edit($id, Request $request)
    {
        $tname = $request->get('tname');
        if(!$id || !$tname)
            return $this->err('标签标号和标签名不可为空');
        $tag_by_tid = db('tag')->find($id);
        $tag_by_tname = db('tag')->where('tname',$tname)->find();
        if(!$tag_by_tid)
            return $this->err('标签不存在');
        if($tag_by_tname && $tag_by_tid['tid'] != $tag_by_tname['tid'])
            return $this->err('标签已存在');
        $suc_count =  db('tag')->where('tid',$id)->setField('tname',$tname);
        return $suc_count? $this->suc('编辑成功'): $this->err('未作任何修改');
    }

    public function delete($id)
    {
        $suc_count = db('tag')->delete($id);
        return $suc_count ? $this->suc('删除成功,删除'.$suc_count.'条') : $this->err('删除失败');

    }
}