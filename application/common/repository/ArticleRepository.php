<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2017/6/1
 * Time: 10:53
 */

namespace app\common\repository;

use app\common\model\Article;
class ArticleRepository extends Article
{
    public static function getArticle($condition = null)
    {
        $query = self::alias('a');
        if (is_array($condition)) {
            $colunms = ['tid'=>'at.'];
            foreach ($condition as $k => $v) {
                if (isset($colunms[$k])) {
                    $query = $query->whereIn('a.aid',function ($query) use ($v){
                        $query->table('lin_article_tag at')
                            ->where('at.tid',$v)
                            ->field('aid');
                    });
                } else {
                    $query = $query->where('a.'.$k, $v);
                }
            }
        }
        $data = $query->paginate(10);
        return $data;
    }

    public static function getById($id)
    {
        $data = self::alias('a')
            ->find($id);
        return $data;
    }

    public static function getPrevArticle($id)
    {
        $prev = self::field('aid, title')
            ->where('aid','lt',$id)
            ->order(['aid'=>'DESC'])
            ->limit(1)->find();
        return $prev ? $prev : 0;
    }

    public static function getNextArticle($id)
    {
        $next = self::field('aid, title')
            ->where('aid','gt',$id)
            ->order(['aid'=>'ASC'])
            ->limit(1)->find();
        return $next ? $next : 0;
    }

    public static function getTopArticle()
    {
        $top_artcile = self::field('aid,title')
            ->where('is_top',1)
            ->order(['click'=>'DESC'])
            ->limit(15)->select();
        return $top_artcile;
    }


}