<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/9/5
 * Time: 17:15
 */

namespace app\common\defined\exception;


use Throwable;

class BadRequestException extends AbstractException
{
    public function __construct($message = "非法请求", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}