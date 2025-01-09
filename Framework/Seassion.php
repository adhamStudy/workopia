<?php

namespace Framework;

class Seassion
{
    /**
     * Start the Session
     * @return void
     */
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a session key/value pari
     * @param string key
     * @param mixed value
     * @return void
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    /**
     * Check if seassion key exists
     * @param string key
     * @return bool 
     * 
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Clear a session by a key
     * @param string key
     * @return void
     * 
     */
    public static function clear($key)
    {
        if (isset($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Clear all Session data
     * @return void
     */
    public static function clearAll()
    {
        session_unset();
        session_destroy();
    }
}
