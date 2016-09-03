<?php
namespace FlaskPHP\Template;
use FlaskPHP\Template;

class TwigTemplate extends Template
{
    private static $cachePath = NULL;

    public static function __render($path, $params)
    {
        $dir = dirname($path);
        $file = basename($path);

        $loader = new Twig_Loader_Filesystem($dir);

        $options = array();
        if (self::$cachePath)
        {
            $options['cache'] = self::$cachePath;
        }

        $twig = new Twig_Environment($loader, $options);


        return $twig->render($file, $params);
    }

}