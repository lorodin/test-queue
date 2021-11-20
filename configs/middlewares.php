<?php

use App\Middlewares\AccountMiddleware;
use App\Middlewares\AmoCrmMiddleware;

return [
    "middleware.amocrm.sendLead" => [AmoCrmMiddleware::class, "next"],
    "middleware.account.processPayment" => [AccountMiddleware::class, "next"]
];
