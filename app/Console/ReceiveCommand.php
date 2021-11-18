<?php

namespace App\Console;

use App\Console\Exceptions\CommandParserException;
use App\Tasks\ReceiveTask;

class ReceiveCommand extends Command
{
    /**
     * @throws CommandParserException
     */
    public function do(...$args): int
    {
        $threads = CommandParser::getArg("-threads", $args);

        if ($threads && !is_int($threads)) {
                throw new CommandParserException("Threads number must be integer");
        }

        $threads = $threads ?? config("threads", 3);

        $tasks = [];

        for ($i = 0; $i < $threads; $i++) {
            $task = $this->app->getTask(ReceiveTask::class);
            $task->run([
                'queue' => env('RABBIT_QUEUE', 'default_queue')
            ]);
            echo "RECEIVE STARTED " . $i . PHP_EOL;
            $tasks[] = $task;
        }

        foreach ($tasks as $task) {
            $task->wait();
        }
        return 0;
    }
}
