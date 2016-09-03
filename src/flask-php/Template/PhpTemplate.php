<?php
namespace FlaskPHP\Template;
use FlaskPHP\Template;

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