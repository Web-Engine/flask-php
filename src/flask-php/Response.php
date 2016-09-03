<?php
namespace FlaskPHP;

class Response {
    private $content = '';
    private $contentType = '';
    private $contentLength = 0;
    private $charset = NULL;

    public function __construct($content, $contentType = 'text/html', $charset = 'utf-8') {
        $this->setContent($content, $contentType, $charset);
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content, $contentType = NULL, $charset = NULL) {
        $this->content = $content;
        $this->contentLength = strlen($content);

        if ($contentType) {
            $this->setContentType($contentType);
        }

        if ($charset) {
            $this->setCharset($charset);
        }
    }

    public function getContentType() {
        return $this->contentType;
    }

    public function setContentType($contentType) {
        $this->contentType = $contentType;
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
}