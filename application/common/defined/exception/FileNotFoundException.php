<?php
/**
 * Created by PhpStorm.
 * User: repu
 * Date: 2017/9/9
 * Time: 16:22
 */

namespace app\common\defined\exception;


use Throwable;

class FileNotFoundException extends AbstractException
{
    public function __construct($message = "文件不存在", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}