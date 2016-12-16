<?php
namespace FlaskPHP\Template;
use FlaskPHP\Template;

class PhpTemplate extends Template
{
    protected static function _render($path, $params = array())
    {
        extract($params);

        ob_start();
        include $path;
        $content = ob_get_clean();

        return $content;
    }
}