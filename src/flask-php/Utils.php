<?php
function render_twig($path, $params)
{
    return \FlaskPHP\Template\TwigTemplate::render($path, $params, 1);
}

function render_php($path, $params)
{
    return \FlaskPHP\Template::render($path, $params, 1);
}