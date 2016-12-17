<?php
namespace FlaskPHP;

class Request {
    private $vars = '';

    private $props = [
        'method' => '',
        'path' => '',
    ];

    public function __construct($dir) {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $vars = [];

        if ($method != 'GET') {
            parse_str(file_get_contents("php://input"), $vars);
        }

        $vars += $_GET;

        $path = '/';

        if (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];

            $path = strstr($_SERVER['REQUEST_URI'], '?', true);
            if ($path === FALSE) {
                $path = $requestUri;
            }
        }

        $dir = preg_replace('/' . preg_quote(DIRECTORY_SEPARATOR) . '/', '/', $dir);
        $dir = substr($dir, strlen($_SERVER['DOCUMENT_ROOT']));
        $path = substr($path, strlen($dir));

        $this->vars = $vars;
        $this->props['method'] = $method;
        $this->props['path'] = $path;
    }

    public function is($key) {
        return isset($this->vars[$key]);
    }

    public function get($key) {
        return $this->vars[$key];
    }

    public function __get($name)
    {
        if (isset($this->props[$name])) {
            return $this->props[$name];
        } else {
            return NULL;
        }
    }
}