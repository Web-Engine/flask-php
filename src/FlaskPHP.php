<?php
namespace FlaskPHP;

use ReflectionFunction;

class FlaskPHP
{
    private $routes = [];

    public function __construct() {
    }

    public function route($rule, $a, $b = NULL)
    {
        if ($b != NULL) {
            $this->_route($rule, $b, $a);
        } else {
            $this->_route($rule, $a);
        }
    }

    private function _route($rule, $func, $method = '')
    {
        if (is_array($method))
        {
            foreach ($method as $item) {
                $this->route($rule, $func, $item);
            }

            return;
        }

        $rule = str_replace('#', '\#', $rule);

        if (!isset($this->routes[$rule])) {
            $this->routes[$rule] = [];
        }

        $method = strtoupper($method);

        $this->routes[$rule][$method] = $func;
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
        $request = Request::get();

        $path = $request->path;

        $routeFunc = NULL;
        $routeParam = NULL;
        $params = NULL;

        foreach ($this->routes as $rule => $func)
        {
            $rule = preg_quote($rule);
            preg_match_all('#\\\<(?:(string|int|float|rule|uuid)\\\:)?([a-zA-Z_][a-zA-Z0-9_]*)\\\>#', $rule, $matches);

            $count = count($matches[0]);
            $patterns = [];

            for ($i = 0; $i < $count; $i++) {
                $text = $matches[0][$i];
                $type = $matches[1][$i];
                $name = $matches[2][$i];

                switch ($type) {
                    case 'int':
                        $regex = '(\d+)';
                        break;

                    case 'float':
                        $regex = '(\d*\.?\d*)';
                        break;

                    case 'path':
                        $regex = '([^/].*?)';
                        break;

                    case 'uuid':
                        $regex = '([A-Fa-f0-9]{8}\-[A-Fa-f0-9]{4}\-[A-Fa-f0-9]{4}\-[A-Fa-f0-9]{4}\-[A-Fa-f0-9]{12})';
                        break;

                    case '':
                    case 'string':
                        $type = 'string';
                        $regex = '([^/]+)';
                        break;

                    default:
                        $regex = $type;
                        break;
                }

                $cnt = 1;
                $rule = str_replace($text, $regex, $rule, $cnt);

                array_push($patterns, [
                    'name'=> $name,
                    'type'=> $type
                ]);
            }

            if (preg_match('#^/?' . $rule . '/?$#', $path, $values))
            {
                array_shift($values);
                $params = [];

                $count = count($patterns);

                for ($i = 0; $i<$count; $i++) {
                    $value = $values[$i];

                    $pattern = $patterns[$i];
                    $name = $pattern['name'];
                    $type = $pattern['type'];

                    switch ($type) {
                        case 'int':
                            $value = (int)$value;
                            break;

                        case 'float':
                            $value = (float)$value;
                            break;
                    }

                    $params[$name] = $value;
                }

                $routeParam = $params;

                $method = $request->method;

                if (isset($func[$method]))
                {
                    $routeFunc = $func[$method];
                    break;
                }
                else if (isset($func['']))
                {
                    $routeFunc = $func[''];
                    break;
                }
            }
        }

        if ($routeFunc)
        {
            $reflect = new ReflectionFunction($routeFunc);
            $params = [];

            foreach ($reflect->getParameters() as $param) {
                $name = $param->getName();
                array_push($params, $routeParam[$name]);
            }

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