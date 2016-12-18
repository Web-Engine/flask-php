<?php
namespace FlaskPHP;

/**
 * @property string method
 * @property array headers
 * @property array args
 * @property array form
 * @property array values
 * @property array cookies
 * @property array files
 * @property string data
 *
 * @property string host
 * @property bool isHttps
 * @property string scheme
 * @property string domain
 * @property string path
 * @property string url
 * @property string baseUrl
 * @property string urlRoot
 * @property string scriptRoot
 * @property bool isXhr
 */
class Request {
    private $method = '';
    private $headers = [];
    private $args = [];
    private $form = [];
    private $values = [];
    private $cookies = [];
    private $files = [];
    private $data = '';

    private $host = '';
    private $isHttps = FALSE;
    private $scheme = '';
    private $domain = '';
    private $path = '';
    private $url =  '';
    private $baseUrl = '';
    private $urlRoot = '';
    private $scriptRoot = '';
    private $isXhr = FALSE;

    /**
     * @var Request
     */
    private static $_request;

    public static function get() {
        if (Request::$_request) return Request::$_request;

        Request::$_request = new Request();
        return Request::$_request;
    }

    private function __construct() {
        // get method
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $this->method = $method;

        // get headers
        if (!function_exists('apache_request_headers')) {
            $headers = [];
            foreach ($_SERVER as $name => $value)
            {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
        } else {
            $headers = apache_request_headers();
        }

        $this->headers = $headers;

        // get args
        $this->args = $_GET;

        // get body
        $data = file_get_contents("php://input");
        $this->data = $data;

        // get form
        parse_str($data, $form);
        $this->form = $form;

        // get values
        $this->values = $form + $_GET;

        // get files
        $this->files = $_FILES;

        // get cookies
        $this->cookies = $_COOKIE;

        // get host
        $host = $_SERVER['HTTP_HOST'];
        $this->host = $host;

        // get domain
        $isHttps = (isset($_SERVER['HTTPS']) && !in_array($_SERVER['HTTPS'], ['off', 'no']));
        $this->isHttps = $isHttps;

        $scheme = isset($_SERVER['REQUEST_SCHEME'])  ? $_SERVER['REQUEST_SCHEME'] : $isHttps ? 'https' : 'http';
        $this->scheme = $scheme;

        $domain = "{$scheme}://{$host}";
        $this->domain = $domain;

        // get url
        $url = $domain . $_SERVER['REQUEST_URI'];
        $this->url = $url;

        // get base url
        $baseUrl = strstr($url, '?', TRUE);
        if ($baseUrl === FALSE) {
            $baseUrl = $url;
        }

        $this->baseUrl = $baseUrl;

        // get script root
        $scriptRoot = dirname($_SERVER['SCRIPT_NAME']);
        $this->scriptRoot = $scriptRoot;

        // get url root
        $urlRoot = $domain . $scriptRoot;
        $this->urlRoot = $urlRoot;

        // get path
        $path = $_SERVER['REQUEST_URI'];
        $path = substr($path, strlen($scriptRoot));

        if (empty($path)) {
            $path = '/';
        }

        $this->path = $path;

        $this->isXhr = isset($headers['Content-Type']) && $headers['Content-Type'] === 'XMLHttpRequest';
    }

    public function args($key, $default) {
        return isset($this->args[$key]) ? $this->args[$key] : $default;
    }

    public function form($key, $default) {
        return isset($this->form[$key]) ? $this->form[$key] : $default;
    }

    public function values($key, $default) {
        return isset($this->values[$key]) ? $this->values[$key] : $default;
    }

    private $_json = NULL;
    private $_jsonError = 0;
    private $_jsonErrorMsg = '';

    public function json($force = FALSE) {
        if (!$force || !$this->isXhr) return NULL;
        if ($this->_json != NULL) return $this->_json;
        if ($this->_jsonError != NULL) return NULL;

        $this->_json = json_decode($this->data);
        $this->_jsonError = json_last_error();

        return $this->_json;
    }

    public function jsonError() {
        return $this->_jsonError;
    }

    public function jsonErrorMsg() {
        return $this->_jsonErrorMsg;
    }

    public function __get($name)
    {
        if (substr($name, 0, 1) != '_' && isset($this->{$name})) {
            return $this->{$name};
        } else {
            return NULL;
        }
    }

    public function __isset($name)
    {
        if (substr($name, 0, 1) != '_' && isset($this->{$name})) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}