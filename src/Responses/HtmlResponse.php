<?php
require_once '../Response.php';

class HtmlResponse extends Response
{
    public function __construct($html)
    {
        parent::__construct($html);
    }
}