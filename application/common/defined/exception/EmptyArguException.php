<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/9/7
 * Time: 13:51
 */

namespace app\common\defined\exception;


use Throwable;

class EmptyArguException extends AbstractException
{
    public function __construct($message = "参数不得为空", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}