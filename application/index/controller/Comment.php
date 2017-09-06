<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/8/7
 * Time: 9:25
 */

namespace app\index\controller;


use app\common\controller\IndexBase;
use app\common\dao\UserDao;
use app\common\defined\exception\BadRequestException;
use app\common\defined\exception\ObjectNotFoundException;
use app\common\defined\exception\ParameterException;
use app\common\defined\exception\ValidateException;
use think\Loader;
use think\Request;
use app\common\model\Comment as CommentModel;

class Comment extends IndexBase
{
    public function create(Request $request)
    {
        $this->isLogin($request);
        $data = $this->setCommentInfo($request);
        $validate = Loader::validate('Comment');
        if (!$validate->check($data)) {
            throw new ValidateException($validate->getError());
        }
        $comment = new CommentModel($data);
        $comment->save();
        return $this->suc('评论添加成功');
    }

    public function update(Request $request, $id)
    {
        $this->isLogin($request);
        $data['content'] = $request->put('content');
        $comment = CommentModel::get($id);
        if (!$comment)
            return $this->err('评论不存在');

        $validate = Loader::validate('Comment');
        if (!$validate->check($data)) {
            return $this->err($validate->getError());
        }

        if ($comment->uid != session('uid'))
            return $this->err('不允许更改他人评论');
        $comment->save($data, ['cmid' => $id]);
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
        $comments = $comment_model->where('aid', $aid)->with(['user', 'parent'])->select();
        return $this->suc(['data' => $comments]);
    }

    /**
     * 前台判断用户是否登入
     * @param Request $request
     */
    private function isLogin(Request $request)
    {
        if (session('uid')) {
            return session('uid');
        } elseif (cookie('uid') && cookie('token')){
            if (md5(md5('guijianshi' . cookie('uid'))) == cookie('token')) {
                session('uid', cookie('uid'));
                return cookie('uid');
            } else {
                throw new BadRequestException();
            }
        } else {
            $method = $request->method();
            $openid = $request->$method('openid');
            $type = $request->$method('type');
            $username = $request->$method('username');
            $avatar = $request->$method('avatar');

            if (!$openid || !$type || !$username || !$avatar)
                throw new ParameterException();

            $userDao = new UserDao();
            $user = UserDao::get(['openid' => $openid, 'type' => $type]);
            if (!$user) {
                $data = [];
                $data['openid'] = $openid;
                $data['username'] = $username;
                $data['avatar'] = $avatar;
                $data['type'] = $type;
                $user = $userDao->add($data);
            }

            session('uid', $user->uid);
            cookie('uid', $user->uid);
            cookie('token', md5(md5('guijianshi' . $user->uid)));
            return true;
        }
    }

    private function setCommentInfo(Request $request)
    {
        $data['content'] = $request->post('content');
        $data['pid'] = $request->post('pid', 0);
        $data['uid'] = session('uid');
        $aid = $request->post('aid');
        if (!model('article')->find($aid))
            throw new ObjectNotFoundException('文章不存在');
        $data['aid'] = $aid;
        return $data;
    }
}