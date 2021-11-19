<?php

use App\Console\Command;
use App\Models\RabbitInfo;
use App\Tasks\traits\RabbitTask;
use App\Utils\Logger\FileLogger;
use App\Utils\Logger\Logger;
use DI\Container;

return [
    RabbitInfo::class => function (Container $c) {
        return new RabbitInfo(
            env('RABBIT_HOST', 'localhost'),
            env('RABBIT_PORT', 5672),
            env('RABBIT_USER', 'guest'),
            env('RABBIT_PASS', 'guest')
        );
    },
    Logger::class => \DI\create(FileLogger::class)->constructor(config("logsDir", APP_ROOT . "/logs")),
    Command::class => \DI\create(Command::class)->constructor(
        [
            "app" => \DI\get("App")
        ]
    ),
    RabbitTask::class => \DI\create(RabbitTask::class)->constructor(
        [
            "app" => \DI\get("App"),
            "rabbit" => \DI\get("RabbitInfo")
        ]
    )
];
