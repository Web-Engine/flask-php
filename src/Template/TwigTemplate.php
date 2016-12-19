<?php
namespace FlaskPHP\Template;
use FlaskPHP\Response;
use FlaskPHP\Template;
use Twig_Loader_Filesystem;
use Twig_Environment;

class TwigTemplate extends Template
{
    private static $cachePath = NULL;

    protected static function _render($path, $params = [])
    {
        $dir = dirname($path);
        $file = basename($path);

        $loader = new Twig_Loader_Filesystem($dir);

        $options = [];
        if (self::$cachePath !== NULL) {
            $options['cache'] = self::$cachePath;
        }

        $twig = new Twig_Environment($loader, $options);


        return new Response($twig->render($file, $params));
    }

}