<?php
namespace FlaskPHP\Response;
use FlaskPHP\Response;

class Redirect extends Response {
    public function __construct($url)
    {
        parent::__construct($url, NULL, NULL);
    }

    public function printAll()
    {
        header('Location: ' . $this->getContent());
        exit;
    }
}
