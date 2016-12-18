<?php
function render_twig($path, $params) {
    return \FlaskPHP\Template\TwigTemplate::render($path, $params);
}

function render_php($path, $params) {
    return \FlaskPHP\Template::render($path, $params);
}

function url_for($name) {
    $request = \FlaskPHP\Request::get();
    return $request->scriptRoot . $name;
}

function redirect($url) {
    return new \FlaskPHP\Response\Redirect($url);
}