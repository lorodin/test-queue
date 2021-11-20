<?php

namespace Router;

use App\Console\HelpCommand;
use App\Console\ReceiveCommand;
use App\Console\SendCommand;

return [
    "console.send" => [SendCommand::class, 'do'],
    "console.receive" => [ReceiveCommand::class, 'do'],
    "console.help" => [HelpCommand::class, 'do'],
];
