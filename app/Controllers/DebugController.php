<?php

namespace App\Controllers;

class DebugController
{
    public function logAction(string $message) {
        echo "DebugAction::logAction " . $message . PHP_EOL;
    }
}
