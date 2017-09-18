<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/8/7
 * Time: 9:26
 */

namespace app\admin\controller;


use app\common\controller\AdminBase;
use app\common\defined\exception\ObjectNotFoundException;
use app\common\defined\exception\ParameterException;
use think\Request;

class Comment extends AdminBase
{
    public function create()
    {

    }

    public function update()
    {

    }

    public function delete($cmid)
    {
        $commentModel = new \app\common\model\Comment();
        if (!is_numeric($cmid))
            throw new ParameterException();
        $comment = $commentModel->find($cmid);
        if (!$comment)
            throw new ObjectNotFoundException();
        $ret = $comment->delete();
        if ($ret)
            return $this->suc('删除成功');
        else
            return $this->err('删除失败');
    }

    public function showList(Request $request)
    {
        $page = $request->get('page', 1);
        $size = $request->get('size', 15);
        $offset = ($page - 1) * $size;
        $commentModel = new \app\common\model\Comment();
        $data = $commentModel->getList($offset, $size)->select();
        $total = $commentModel->count();
        return $this->suc(['data' => $data, 'total' => $total]);
    }

    public function getOne()
    {

    }
}