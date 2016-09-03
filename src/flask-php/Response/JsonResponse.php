<?php
namespace FlaskPHP\Response;
use FlaskPHP\Response;

class JsonResponse extends Response {
    public function __construct($data)
    {
        $content = json_encode($data);

        parent::__construct($content, 'application/json');
    }
}