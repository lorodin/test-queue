<?php

use App\Config;
use App\Console\Command;
use App\Models\RabbitInfo;
use App\Services\CommandsManagerService;
use App\Services\ControllersManagerService;
use App\Services\RabbitMqApiService;
use App\Services\TasksManagerService;
use App\Utils\Logger\FileILogger;
use App\Utils\Logger\ILogger;
use DI\Container;

return [
    RabbitInfo::class => function () {
        return new RabbitInfo(
            env('RABBIT_HOST', 'localhost'),
            env('RABBIT_PORT', 5672),
            env('RABBIT_USER', 'guest'),
            env('RABBIT_PASS', 'guest')
        );
    },

    CommandsManagerService::class => function (Container $c) {
        return new CommandsManagerService($c);
    },
    RabbitMqApiService::class => function (Container $c) {
        return new RabbitMqApiService($c->get(RabbitInfo::class));
    },
    TasksManagerService::class => function (Container $c) {
        return new TasksManagerService($c);
    },
    ControllersManagerService::class => function (Container $c) {
        return new ControllersManagerService($c);
    },

    ILogger::class => function () {
        return new FileILogger(Config::get("logsDir", APP_ROOT . "/logs"));
    },
    Command::class => function (Container $c) {
        return new Command($c->get(CommandsManagerService::class));
    }
];
