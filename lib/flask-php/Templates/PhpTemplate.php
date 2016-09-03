<?php
namespace FlaskPHP\Template;

require_once '../Template.php';

abstract class PhpTemplate extends Template
{
    public static function render($path, $params) {
        extract($params);

        $path = parent::getCallerPath($path);

        ob_start();
        include $path;
        $content = ob_get_clean();

        return $content;
    }
}