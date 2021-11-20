<?php

namespace App\Middlewares;

use App\Controllers\Flagmer\Integrations\Amocrm\sendLeadDto;
use App\Models\Request;

class AmoCrmMiddleware
{
    public function next(Request $request, callable $next) {
        $request = $request->validate(['lead_id']);

        $sendLedDto = new sendLeadDto();

        $sendLedDto->lead_id = $request['lead_id'];

        call_user_func($next, $sendLedDto);
    }
}
