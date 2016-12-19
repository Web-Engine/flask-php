<?php
namespace FlaskPHP\Template;
use FlaskPHP\Response;
use FlaskPHP\Template;

class PhpTemplate extends Template
{
    protected static function _render($path, $params = [])
    {
        extract($params);

        ob_start();
        include $path;
        $content = ob_get_clean();

        return new Response($content);
    }
}