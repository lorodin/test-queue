<?php

use App\Controllers\DebugController;
use App\Controllers\Flagmer\Billing\Account;
use App\Controllers\Flagmer\Integrations\AmoCrm;

return [
    "controller.debug.log" => [DebugController::class, "logAction"],
    "controller.amocrm.sendLead" => [AmoCrm::class, "sendLeadAction"],
    "controller.account.processPayment" => [Account::class, "processPaymentAction"]
];
