<?php

use App\Controllers\DebugController;
use App\Controllers\Flagmer\Billing\Account;
use App\Controllers\Flagmer\Integrations\AmoCrm;

return [
    "debug.log" => [
        "action" => [DebugController::class, "log"],
        "middlewares" => [
            "debug.log"
        ]
    ],
    "amocrm.sendLead" => [
        "action"     => [AmoCrm::class, "sendLead"],
        "middlewares" => [
            "amocrm.sendLead"
        ]
    ],
    "account.processPayment" => [
        "action"     => [Account::class, "processPayment"],
        "middlewares" => [
            "account.processPayment"
        ]
    ]
];
