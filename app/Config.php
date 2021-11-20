<?php

namespace App;

use DI\Container;
use Exception;

final class Config
{
    private static Container $container;

    public static function init(Container $container)
    {
        self::$container = $container;
    }

    /**
     * @throws Exception
     */
    public static function get(string $key, $defaultValue = null)
    {
        if (!self::$container) {
            throw new Exception("Configurations not initialized");
        }

        $key = "config.$key";

        return self::$container->has($key) ? self::$container->get($key) : $defaultValue;
    }
}
