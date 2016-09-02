<?php

class FlaskPHP
{
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

    }
}