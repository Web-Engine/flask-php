<?php
namespace FlaskPHP;

class FlaskPHP
{
    private static $requestMethod = NULL;
    private static $params = NULL;

    public static function init()
    {
        self::$requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

        if (self::$requestMethod != 'GET') {
            parse_str(file_get_contents("php://input"), self::$params);
        }
        else {
            self::$params = $_GET;
        }
    }

    private $name;
    private $routes = array();

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function route($rule, $def, $methods = NULL)
    {
        if (is_array($methods))
        {
            foreach ($methods as $method) {
                $this->route($rule, $def, $method);
            }

            return;
        }

        if (!isset($this->routes[$rule])) {
            $this->routes[$rule] = array();
        }

        $methods = strtoupper($methods);

        $this->route[$rule][$methods] = $def;
    }

    public function run()
    {
        $path = '/';

        if (!empty($_SERVER['PATH_INFO']))
        {
            $path = $_SERVER['PATH_INFO'];
        }
        else if (!empty($_SERVER['ORIG_PATH_INFO']) && $_SERVER['ORIG_PATH_INFO'] !== '/index.php')
        {
            $path = $_SERVER['ORIG_PATH_INFO'];
        }
        else
        {
            if (!empty($_SERVER['REQUEST_URI']))
            {
                $path = (strpos($_SERVER['REQUEST_URI'], '?') > 0) ? strstr($_SERVER['REQUEST_URI'], '?', true) : $_SERVER['REQUEST_URI'];
            }
        }

        $targetDef = null;
        $params = null;

        foreach ($this->routes as $rule => $defs)
        {
//          preg_match_all('@<(?:(string|int|float|rule|any|uuid):)?([a-zA-Z0-9_]+)>@', $rule, $matches);
            preg_match_all('@<(?:(string|int|float|rule|uuid):)?([a-zA-Z0-9_]+)>@', $rule, $matches);

            $count = array($matches[0]);

            for ($i = 0; $i < $count; $i++) {
                $text = $matches[0][$i];
                $converter = $matches[1][$i];
//                $name = $matches[2][$i];

                switch ($converter) {
                    case 'int':
                        $regex = '(\d+)';
                        break;

                    case 'float':
                        $regex = '(\d+\.\d+)';
                        break;

                    case 'path':
                        $regex = '([^/].*?)';
                        break;

//                    case 'any':
//                        break;

                    case 'uuid':
                        $regex = '([A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12})';
                        break;

                    case 'string':
                    default:
                        $regex = '[^/]+';
                        break;
                }

                $rule = preg_replace('@' . $text . '@', $regex, $rule);
            }

            if (preg_match('@^/?' . $rule . '/?$@', $path, $matches))
            {
                if ($defs[self::$requestMethod])
                {
                    $targetDef = $defs[self::$requestMethod];
                    $params = $matches;
                    unset($params[0]);
                }
                else if ($defs[NULL])
                {
                    $targetDef = $defs[NULL];
                    $params = $matches;
                    unset($params[0]);
                }

                break;
            }
        }

        if ($targetDef)
        {
            $result = call_user_func_array($targetDef, $params);
        }
        else
        {
            $result = new Response('Cannot found a page');
        }

        if ($result instanceof Response) {
            $type = $result->getContentType();
            $content = $result->getContent();
        }
        else {
            $type = 'text/html';
            $content = $result;
        }

        header('Content-type: ' . $type . '; charset=utf-8');
        header('Expires: Mon, 26, Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        echo $content;
        exit;
    }
}

FlaskPHP::init();