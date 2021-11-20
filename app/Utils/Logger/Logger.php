<?php

namespace App\Utils\Logger;

final class Logger
{
    private static ILogger $_instance;

    public static function init(ILogger $logger)
    {
        self::$_instance = $logger;
    }

    public static function instance(): ?ILogger
    {
        return self::$_instance ?? DebugLogger::instance();
    }

    public static function info(string $tag, string $message)
    {
        self::instance()->logI($tag, $message);
    }

    public static function error(string $tag, string $message)
    {
        self::instance()->logE($tag, $message);
    }

    public static function debug(string $tag, string $message)
    {
        self::instance()->logD($tag, $message);
    }
}
