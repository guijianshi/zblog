<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/8/7
 * Time: 9:25
 */

namespace app\index\controller;


use app\common\controller\IndexBase;
use think\Loader;
use think\Request;
use app\common\model\Comment as CommentModel;

class Comment extends IndexBase
{
    public function create(Request $request)
    {
        $data['content'] = $request->post('content');
        $data['pid'] = $request->post('pid',0);
        $data['auid'] = 1;
        $data['aid'] = $request->post('aid');

        $validate = Loader::validate('Comment');
        if(!$validate->check($data)){
            return $this->err($validate->getError());
        }
        $comment = new CommentModel($data);
        $comment->save();
        return $this->suc('评论添加成功');
    }

    public function update(Request $request, $id)
    {
        $data['content'] = $request->put('content');
        $auid = 1;
        $comment = CommentModel::get($id);
        if (!$comment)
            return $this->err('评论不存在');

        $validate = Loader::validate('Comment');
        if(!$validate->check($data)){
            return $this->err($validate->getError());
        }

        if ($comment->auid != $auid)
            return $this->err('不允许更改他人评论');
        $comment->save($data,['cmid'=>$id]);
        return $this->suc('评论更新成功');
    }

    public function delete($id)
    {
        $comment = CommentModel::get($id);
        if (!$comment)
            return $this->err('评论不存在');
        $comment->delete();
        return $this->suc('评论删除成功');
    }

    public function show($aid)
    {
        $comment_model = new CommentModel();
        $article = model('article')->find($aid);
        if (!$article)
            return $this->err('文章不存在');
        $comments = $comment_model->where('aid', $aid)->select();
        return $this->suc(['data' => $comments]);
    }
}