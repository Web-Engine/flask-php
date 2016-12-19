<?php
namespace FlaskPHP;

class Response {
    private $headers = [];

    private $content = '';
    private $contentLength = 0;

    private $contentType = '';
    private $charset = '';

    public function __construct($content, $contentType = 'text/html', $charset='utf-8') {
        $this->setContent($content);
        $this->setContentType($contentType, $charset);
    }

    public function setCookie($name, $value = NULL, $expire = NULL, $path = NULL, $domain = NULL, $secure = NULL, $httponly = NULL) {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public function setHeader($header, $content = NULL) {
        if ($content === NULL) {
            if (strpos($header, ':') === FALSE) {
                array_push($this->headers, $header);
                return;
            }

            list($header, $content) = explode(':', $header, 2);
        }

        $header = str_replace(' ', '-', ucwords(str_replace('-', ' ', $header)));
        $this->headers[$header] = $content;
    }

    public function setContentType($contentType, $charset='utf-8') {
        $this->contentType = $contentType;
        $this->charset = $charset;
        $this->setHeader('Content-Type', "{$contentType}; charset={$charset}");
    }

    public function getContentType() {
        return $this->getHeader('Content-Type');
    }

    public function getHeader($header) {
        return $this->headers[$header];
    }

    public function getAllHeader($header) {
        return $this->headers;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
        $this->contentLength = strlen($content);
    }

    public function getCharset() {
        return $this->charset;
    }

    public function setCharset($charset) {
        $this->charset = $charset;
    }

    public function getContentLength() {
        return $this->contentLength;
    }

    public function printAll() {
        foreach ($this->headers as $header=>$value) {
            header("{$header}: {$value}");
        }

        echo $this->getContent();
    }
}