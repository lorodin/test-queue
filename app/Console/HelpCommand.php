<?php

namespace App\Console;

class HelpCommand extends Command
{
    function do(...$args): int
    {
        echo "Application help" . PHP_EOL;
        echo "use: " . PHP_EOL ."php app.php <cmd> [options]" . PHP_EOL . PHP_EOL;
        echo "cmd - send, receive" . PHP_EOL;
        echo "  send    - send message to rabbit" . PHP_EOL;
        echo "  receive - start message receiver" . PHP_EOL . PHP_EOL;
        echo "Send options:" . PHP_EOL;
        echo "  -type <json, message> - send message from cli or parse json file" . PHP_EOL;
        echo "   <path>                - path to JSON file (for json type)" . PHP_EOL;
        echo "  '<message text>'      - text message (for message type)" . PHP_EOL;

        return 0;
    }
}
