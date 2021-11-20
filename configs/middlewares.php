<?php

use App\Middlewares\AccountMiddleware;
use App\Middlewares\AmoCrmMiddleware;
use App\Middlewares\DebugMiddleware;
use DI\Container;

return [
    "amocrm.sendLead" => function () {
        return new AmoCrmMiddleware();
    },
    "account.processPayment" => function () {
        return new AccountMiddleware();
    },
    "debug.log" => function () {
        return new DebugMiddleware();
    }
];
