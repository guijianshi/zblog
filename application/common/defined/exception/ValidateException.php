<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/9/6
 * Time: 9:31
 */

namespace app\common\defined\exception;


use Throwable;

class ValidateException extends AbstractException
{
    public function __construct($message = "数据验证错误", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}