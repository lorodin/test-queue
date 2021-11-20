<?php

use App\Services\ControllersManagerService;
use App\Services\RabbitMqApiService;
use App\Tasks\ReceiveTask;
use DI\Container;

return [
    "receive" => function (Container $c) {
        return new ReceiveTask($c->get(RabbitMqApiService::class), $c->get(ControllersManagerService::class));
    }
];
