<?php
namespace FlaskPHP;

abstract class Template
{
    public static function render($path, $params, $count = 0)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, $count + 2);
        $callerDir = dirname($backtrace[$count + 1]['file']);

        return self::__render($callerDir . '/' . $path, $params);
    }

    public abstract static function __render($path, $params);
}