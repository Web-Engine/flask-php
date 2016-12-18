<?php
namespace FlaskPHP;

use Exception;

class Session
{
    /**
     * @var Session
     */
    private static $_session = NULL;
    public static function get() {
        if (Session::$_session !== NULL) return Session::$_session;

        Session::$_session = new Session();
        return Session::$_session;
    }

    private function __construct() {
        assert(@session_start(), new Exception('Failed to start session'));
    }

    public function __get($name)
    {
        if (!isset($_SESSION[$name])) {
            return NULL;
        }

        return $_SESSION[$name];
    }

    public function __isset($name)
    {
        return isset($_SESSION[$name]);
    }

    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function __unset($name)
    {
        unset($_SESSION[$name]);
    }
}