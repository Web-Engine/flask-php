<?php
namespace FlaskPHP;

abstract class Template
{
    public static function render($path, $params)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        $callerDir = dirname($backtrace[1]['file']);

        return self::__render($callerDir . '/' . $path, $params);
    }

    public abstract static function __render($path, $params);
}