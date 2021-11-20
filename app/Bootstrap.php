<?php

namespace App;

use App\Utils\Logger\ILogger;
use App\Utils\Logger\Logger;
use DI\Container;
use DI\ContainerBuilder;
use Exception;

final class Bootstrap
{
    /**
     * @throws Exception
     */
    public static function load() :Container
    {
        $builder = new ContainerBuilder();

        $builder->addDefinitions(APP_ROOT . "/configs/core.php");

        $configs = [];
        $controllers = [];
        $middlewares = [];

        $array = require APP_ROOT . "/configs/config.php";

        array_walk($array, function ($value, $key) use (&$configs) {
            $configs["config.$key"] = $value;
        });

        $builder->addDefinitions($configs);

        $container = $builder->build();

        Config::init($container);


        $array = require APP_ROOT . "/configs/controllers.php";

        array_walk($array, function ($value, $key) use ($container) {
            $container->set("controller.$key", $value);
        });

        $array = require APP_ROOT . "/configs/middlewares.php";

        array_walk($array, function ($value, $key) use ($container) {
            $container->set("middleware.$key", $value);
        });

        $array = require APP_ROOT . "/configs/console.php";

        array_walk($array, function ($value, $key) use ($container) {
            $container->set("console.$key", $value);
        });

        $array = require APP_ROOT . "/configs/tasks.php";

        array_walk($array, function ($value, $key) use ($container) {
            $container->set("task.$key", $value);
        });

        Logger::init($container->get(ILogger::class));

        return $container;
    }
}
