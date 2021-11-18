<?php

namespace App\Console;

use App\Console\Exceptions\CommandParserException;
use App\Readers\DebugReader;
use App\Readers\JsonReader;
use App\Tasks\SendTask;

class SendCommand extends Command
{
    /**
     * @throws CommandParserException
     */
    public function do(...$args) : int
    {
        $task = $this->app->getTask(SendTask::class);

        $type = CommandParser::getArg('-type', $args, true, ["json", "message"]);

        if (count($args) < 5) {
            throw new CommandParserException(($type == "json" ? "Path to json file" : "Message text") . " not set");
        }

        $task->run([
            'queue' => env('RABBIT_QUEUE', 'default_queue'),
            'reader' => $type == 'json' ? new JsonReader($args[4]) : new DebugReader($args[4])
        ]);

        return 0;
    }
}
