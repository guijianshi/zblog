<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/9/7
 * Time: 14:10
 */

namespace app\common\defined\exception;


use Throwable;

class UploadException extends AbstractException
{
    public function __construct($message = "文件上传失败", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}