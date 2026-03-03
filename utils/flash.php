<?php

class Flash
{
    public static function set($message, $type = 'success')
    {
        $_SESSION['flash'] = ['message' => $message, 'type' => $type];
    }

    public static function success($message)
    {
        self::set($message, 'success');
    }

    public static function error($message)
    {
        self::set($message, 'error');
    }

    public static function get()
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }
}

?>