<?php
namespace FlaskPHP\Template;

abstract class PhpTemplate extends Template
{
    public static function __render($path, $params)
    {
        extract($params);

        ob_start();
        include $path;
        $content = ob_get_clean();

        return $content;
    }
}