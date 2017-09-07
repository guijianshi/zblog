<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/9/7
 * Time: 13:37
 */

namespace app\common\util;


use app\common\defined\exception\EmptyArguException;
use app\common\defined\exception\ParameterException;
use app\common\defined\exception\UploadException;
use think\Request;

class FileUpload
{
    public $uploadPath = '/upload/';

    public function uploadImg(Request $request)
    {
        $date = date('Y-m-d');
        $this->uploadPath = $this->uploadPath . 'img/' . $date . '/';
        $img = $request->post('img');
        if (empty($img))
            throw new EmptyArguException();

        // 获取图片
        try {
            list($type, $data) = explode(',', $img);
        } catch (\Exception $e) {
            throw new ParameterException();
        }

        // 判断类型
        if (strstr($type, 'image/jpeg') != '') {
            $ext = '.jpg';
        } elseif (strstr($type, 'image/gif') != '') {
            $ext = '.gif';
        } elseif (strstr($type, 'image/png') != '') {
            $ext = '.png';
        } else {
            throw new ParameterException('图片格式不支持');
        }
        $dirname = APP_PATH . '../public' . $this->uploadPath;
        if (!is_dir($dirname)) {
            mkdir($dirname, 0777);
        }
        // 生成的文件名
        $photo = time() . $ext;

        // 生成文件
        $ret = file_put_contents($dirname.$photo, base64_decode($data), LOCK_EX);
        if ($ret === false) {
            throw new UploadException();
        }
        return array('img' => $this->uploadPath . $photo);
    }
}