<?php

namespace App\Controllers;

use App\Models\Request;

class DebugController
{
    public function logAction(Request $request) {
        $validate = $request->validate(['message']);

        echo "DebugAction::logAction " . $validate['message'] . PHP_EOL;
    }
}
