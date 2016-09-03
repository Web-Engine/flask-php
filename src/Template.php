<?php
abstract class Template {
    public static function getCallerPath($path, $count = 3)  {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, $count);
        $callerDir = dirname($backtrace[$count - 1]['file']);

        return $callerDir . '/' . $path;
    }

    public abstract static function render($path, $params);
}