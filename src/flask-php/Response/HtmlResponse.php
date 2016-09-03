<?php
namespace FlaskPHP\Response;
use FlaskPHP\Response;

class HtmlResponse extends Response
{
    public function __construct($html)
    {
        parent::__construct($html);
    }
}