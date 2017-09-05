<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2017/9/5
 * Time: 10:03
 */

namespace app\common\defined\exception;


use Throwable;

abstract class AbstractException extends \RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}