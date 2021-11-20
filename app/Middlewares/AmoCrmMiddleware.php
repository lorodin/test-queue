<?php

namespace App\Middlewares;

use App\Controllers\Flagmer\Integrations\Amocrm\sendLeadDto;
use App\Requests\AmoCrmSetLeadRequest;

class AmoCrmMiddleware implements Middleware
{
    public function next($request, callable $next) {
        $request = new AmoCrmSetLeadRequest($request);

        $request->validate();

        $sendLedDto = new sendLeadDto();

        $sendLedDto->lead_id = $request->lead_id;

        call_user_func($next, $sendLedDto);
    }
}
