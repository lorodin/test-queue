<?php

namespace App\Console;

use App\Config;
use App\Console\Exceptions\CommandParserException;
use App\Services\TasksManagerService;
use App\Tasks\ReceiveTask;
use Exception;

class ReceiveCommand
{
    private TasksManagerService $tasksManagerService;

    public function __construct(TasksManagerService $tasksManagerService)
    {
        $this->tasksManagerService = $tasksManagerService;
    }

    /**
     * @throws CommandParserException
     * @throws Exception
     */
    public function do(...$args): int
    {
        $threads = $args[0] ?? null;

        if ($threads && !is_int($threads)) {
            throw new CommandParserException("Threads number must be integer");
        }

        $threads = $threads ?? Config::get("threads", 3);

        $tasks = [];

        echo "Make receive processes ($threads)" . PHP_EOL;

        for ($i = 0; $i < $threads; $i++) {
            $tasks[] = $this->tasksManagerService->create("receive");
        }

        echo "Receive processes created ($threads) start tasks" . PHP_EOL;

        foreach ($tasks as $task) {
            $args['queue'] = env("RABBIT_QUEUE", "example_queue");
            $task->run($args);
        }

        foreach ($tasks as $task) {
            $task->wait();
        }
        return 0;
    }
}
