<?php

namespace App\Utils\Logger;

class DebugLogger implements ILogger
{
    private static DebugLogger $_instance;

    public static function instance():ILogger
    {
        if (!self::$_instance) {
            self::$_instance = new DebugLogger();
        }

        return self::$_instance;
    }

    function logI(string $tag, string $message)
    {
        $date = new \DateTime('now');
        echo "!DEBUG LOGGER![{$date->format("Y-m-d H:i:s.u")}][{$tag}][INFO] ${message}";
    }

    function logD(string $tag, string $message)
    {
        $date = new \DateTime('now');
        echo "!DEBUG LOGGER![{$date->format("Y-m-d H:i:s.u")}][{$tag}][INFO] ${message}";
    }

    function logE(string $tag, string $message)
    {
        $date = new \DateTime('now');
        echo "!DEBUG LOGGER![{$date->format("Y-m-d H:i:s.u")}][{$tag}][INFO] ${message}";
    }
}
