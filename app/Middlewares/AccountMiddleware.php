<?php

namespace App\Middlewares;

use App\Controllers\Flagmer\Billing\Account\processPaymentDto;
use App\Requests\AccountPaymentRequest;

class AccountMiddleware implements Middleware
{
    public function next($data, callable $next) {
        $request = new AccountPaymentRequest($data);
        $request->validate();

        $processPaymentInfo = new processPaymentDto();

        $processPaymentInfo->account_id = $request->account_id;
        $processPaymentInfo->amount = $request->amount;

        $next($processPaymentInfo);
    }
}
