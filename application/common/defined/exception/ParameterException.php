<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/9/5
 * Time: 10:04
 */

namespace app\common\defined\exception;


use Throwable;

class ParameterException extends AbstractException
{
    public function __construct($message = "参数错误", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}