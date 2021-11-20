<?php

namespace App\Controllers;

use App\Requests\DebugRequest;

class DebugController
{
    public function logAction(DebugRequest $request) {
        echo "DebugAction::logAction " . $request->message . PHP_EOL;
    }
}
