<?php

namespace Router;

use App\Console\HelpCommand;
use App\Console\ReceiveCommand;
use App\Console\SendCommand;

return [
    "send"    => SendCommand::class,
    "receive" => ReceiveCommand::class,
    "help"    => HelpCommand::class
];
