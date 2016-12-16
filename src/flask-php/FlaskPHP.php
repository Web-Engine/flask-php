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

    private $dir;
    private $routes = [];

    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    public function route($rule, $a, $b = NULL)
    {
        if ($b != NULL) {
            $this->_route($rule, $b, $a);
        } else {
            $this->_route($rule, $a);
        }
    }

    private function _route($rule, $func, $methods = NULL)
    {
        if (is_array($methods))
        {
            foreach ($methods as $method) {
                $this->route($rule, $func, $method);
            }

            return;
        }

        if (!isset($this->routes[$rule])) {
            $this->routes[$rule] = [];
        }

        $methods = strtoupper($methods);

        $this->routes[$rule][$methods] = $func;
    }

    public function get($rule, $func) {
        $this->route($rule, 'GET', $func);
    }

    public function post($rule, $func) {
        $this->route($rule, 'POST', $func);
    }

    public function patch($rule, $func) {
        $this->route($rule, 'PATCH', $func);
    }

    public function put($rule, $func) {
        $this->route($rule, 'PUT', $func);
    }

    public function delete($rule, $func) {
        $this->route($rule, 'DELETE', $func);
    }

    public function run()
    {
        $requestPath = '/';

        if (!empty($_SERVER['PATH_INFO']))
        {
            $requestPath = $_SERVER['PATH_INFO'];
        }
        else if (!empty($_SERVER['ORIG_PATH_INFO']) && $_SERVER['ORIG_PATH_INFO'] !== '/index.php')
        {
            $requestPath = $_SERVER['ORIG_PATH_INFO'];
        }
        else {
            if (!empty($_SERVER['REQUEST_URI'])) {
                $requestPath = (strpos($_SERVER['REQUEST_URI'], '?') > 0) ? strstr($_SERVER['REQUEST_URI'], '?', true) : $_SERVER['REQUEST_URI'];
            }
        }

        $dir = implode('/', explode(DIRECTORY_SEPARATOR, $this->dir));

        $checkPath = $requestPath;
        $pos = strrpos($checkPath, '/');
        $len = strlen($checkPath);
        if ($pos === $len - 1) {
            $checkPath = substr($checkPath, 0, $len - 1);
        }

        $dirLen = strlen($dir);
        while ($checkPath != '/' && $checkPath != '') {
            $pos = strrpos($this->dir, $checkPath);
            $len = strlen($checkPath);

            if ($pos == $dirLen - $len) {
                $requestPath = substr($requestPath, $len);
                break;
            }

            $checkPath = dirname($checkPath);
        }

        $routeFunc = null;
        $params = null;

        foreach ($this->routes as $rule => $func)
        {
            preg_match_all('@<(?:(string|int|float|rule|uuid):)?([a-zA-Z0-9_]+)>@', $rule, $matches);

            $count = count($matches[0]);

            for ($i = 0; $i < $count; $i++) {
                $text = $matches[0][$i];
                $converter = $matches[1][$i];

                switch ($converter) {
                    case 'int':
                        $regex = '(\d+)';
                        break;

                    case 'float':
                        $regex = '(\d+\.?\d+)';
                        break;

                    case 'path':
                        $regex = '([^/].*?)';
                        break;

                    case 'uuid':
                        $regex = '([A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12})';
                        break;

                    case 'string':
                    default:
                        $regex = '([^/]+)';
                        break;
                }

                $rule = preg_replace('@' . $text . '@', $regex, $rule);
            }

            if (preg_match('@^/?' . $rule . '/?$@', $requestPath, $matches))
            {
                if (isset($func[self::$requestMethod]))
                {
                    $routeFunc = $func[self::$requestMethod];
                    $params = $matches;
                    unset($params[0]);
                }
                else if (isset($func[NULL]))
                {
                    $routeFunc = $func[NULL];
                    $params = $matches;
                    unset($params[0]);
                }

                break;
            }
        }

        if ($routeFunc)
        {
            $result = call_user_func_array($routeFunc, $params);
        }
        else
        {
            $result = new Response('Cannot found a page');
        }

        if (!($result instanceof Response)) {
            $result = new Response($result);
        }

        $result->printAll();
        exit;
    }
}

FlaskPHP::init();