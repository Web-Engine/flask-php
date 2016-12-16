<?php
namespace FlaskPHP;

use Exception;

abstract class Template
{
    private static function getPath($path)
    {
        $count = 3;
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, $count);

        do {
            $count--;
        } while (!isset($backtrace[$count]['file']));

        $callerDir = dirname($backtrace[$count]['file']);

        return $callerDir . '/' . $path;
    }

    public static function render($path, $params = array()) {
        $path = self::getPath($path);

        return static::_render($path, $params);
    }

    protected static function _render($path, $params = array()) {
        throw new Exception('Cannot use Template\'s render method.');
    }
}