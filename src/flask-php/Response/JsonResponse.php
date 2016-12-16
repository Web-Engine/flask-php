<?php
namespace FlaskPHP\Response;
use FlaskPHP\Response;

class JsonResponse extends Response {
    public function __construct($data)
    {
        $content = json_encode($data);

        parent::__construct($content, 'application/json');

        $this->setHeader('Expires', 'Mon, 26, Jul 1997 05:00:00 GMT');
        $this->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . 'GMT');
        $this->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->setHeader('Pragma', 'no-cache');
    }

}