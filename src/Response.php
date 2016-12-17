<?php
namespace FlaskPHP;

class Response {
    private $headers = [];

    private $content = '';
    private $contentLength = 0;
    private $charset = NULL;

    public function __construct($content, $contentType = 'text/html', $charset='utf-8') {
        $this->setContent($content);
        $this->setContentType($contentType, $charset);
    }

    public function setHeader($header, $content) {
        $this->headers[$header] = $content;
    }

    public function setContentType($type, $charset='utf-8') {
        $this->setHeader('Content-Type', "{$type}; charset={$charset}");
    }

    public function getContentType() {
        return $this->getHeader('Content-Type');
    }

    public function getHeader($header) {
        return $this->headers[$header];
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