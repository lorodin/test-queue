<?php

namespace App\Middlewares;

use App\Controllers\Flagmer\Billing\Account\processPaymentDto;
use App\Models\Request;

class AccountMiddleware
{
    public function next(Request $request, callable $next) {
        $request = $request->validate(['account_id', 'amount']);

        $processPaymentInfo = new processPaymentDto();

        $processPaymentInfo->account_id = $request['account_id'];
        $processPaymentInfo->amount = $request['amount'];

        call_user_func($next, $processPaymentInfo);
    }
}
