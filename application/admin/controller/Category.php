<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/4/27
 * Time: 14:24
 */

namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Request;

class Category extends AdminBase
{
    public function add(Request $request)
    {

        $cname = $request->post('cname');
        $keywords = $request->post('keywords');
        $description = $request->post('description');
        $pid = $request->post('pid');
        $icon = $request->post('icon');
        $pic_url = $request->post('pic_url','http://i4.bvimg.com/608112/aa08b9ac86a5da5f.jpg');
        if (!$cname || !$keywords || !$description)
            return $this->err('完善分类内容');
        $exist_category = db('category')->where('cname', $cname)->find();
        if ($exist_category)
            return $this->err('分类已存在');
        $data = [
            'cname' => $cname,
            'keywords' => $keywords,
            'description' => $description,
            'pid' => $pid,
            'icon' => $icon,
            'pic_url' => $pic_url,
        ];
        try {
            $suc_count = db('category')->insert($data);
            return $this->suc(['suc_count' => $suc_count]);
        } catch (\Exception $e) {
            return $this->err('添加失败');
        }
    }

    public function get($id = 0)
    {
        if ($id == 0)
            $data = db('category')->select();
        else
            $data = db('category')->find($id);
        $total = db('category')->count();
        return $this->suc(['data' => $data, 'total' => $total]);
    }

    public function edit(Request $request, $id)
    {
        $category = model('category')->find($id);
        if (!$category)
            return $this->err('分类不存在');

        $cname = $request->put('cname');
        $keywords = $request->put('keywords');
        $description = $request->put('description');
        $pid = $request->put('pid');
        $icon = $request->put('icon');
        $pic_url = $request->put('pic_url','http://i4.bvimg.com/608112/aa08b9ac86a5da5f.jpg');
        if (!$cname || !$keywords || !$description)
            return $this->err('完善分类内容');
        $exist_category_id = db('category')->find($id);
        if (!$exist_category_id)
            return $this->err('分类不存在');
        $exist_category_cname = db('category')
            ->where('cname', $cname)
            ->where('cid','neq', $id)
            ->select();
        if ($exist_category_cname)
            return $this->err('分类名重复');
        $data = [
            'cname' => $cname,
            'keywords' => $keywords,
            'description' => $description,
            'pid' => $pid,
            'icon' => $icon,
            'pic_url' => $pic_url,
        ];
        $suc_count = model('category')->save($data, ['cid' => $id]);
        return $suc_count ? $this->suc($id . '分类编辑成功') : $this->err($id . '编辑失败');


    }

    public function delete($id)
    {
        if (is_int($id))
            return $this->err('id必须是数字');
        $suc_count = db('category')->delete($id);
        if ($suc_count) {
            $articleModel = new \app\common\model\Article();
            $articles = $articleModel->save(['cid' => 1],['cid' => $id]);
        }

        return $suc_count ? $this->suc(['mgs' => '删除成功,删除' . $suc_count . '条', 'article_effect' => $articles])
            : $this->err('删除失败');
    }

    public function getRecursion()
    {
        $categorys = db('category')->order('pid','ASC')->column(['cid value', 'cname label','icon', 'pid', 'pic_url']);
        foreach ($categorys as $item) {
            $categorys[$item['pid']]['children'][] = &$categorys[$item['value']];
        }
        $data = isset($categorys[0]['children']) ? $categorys[0]['children'] : array();
        $data = array_column($data, 'children');
        return $this->suc(['data' => $data[0]]);
    }

    public function getSubs($categorys, $pid = 0, $level = 1)
    {
        $subs = array();
        foreach ($categorys as $category) {
            if ($category['pid'] == $pid) {
                $subs[] = ['value' => $category['cid'], 'label' => str_repeat('| ', $level - 1) . ($level > 1 ? '-' : '') . $category['cname'], 'level' => $level];
                $subs = array_merge($subs, $this->getSubs($categorys, $category['cid'], $level + 1));
            }
        }
        return $subs;
    }

    public function getSub($categorys)
    {
        foreach ($categorys as $item) {
            $categorys[$item['pid']]['children'][$item['value']] = &$categorys[$item['value']];
        }
        return isset($categorys[0]['children']) ? $categorys[0]['children'] : array();
    }
    public function show()
    {
        $data = model('category')->paginate(10);
        return view('', ['categorys' => $data]);
    }

    public function sort(Request $request)
    {
        $sort_list = $request->put()['sort_list'];
        $suc_count = model('category')->saveAll($sort_list);
        return $suc_count ? $this->suc('排序成功') : $this->err('排序失败');
    }


    private function getDataByRequest(Request $request)
    {

    }
}