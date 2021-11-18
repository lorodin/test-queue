<?php

namespace App\Tasks\traits;

use App\Utils\Process;
use Exception;

trait AsyncTask
{
    private Process $thread;
    private string $name;

    public function run(array $params) {
        static $counter = 0;

        $this->thread = new Process();

        $this->name = '#task_' . $counter;
        $counter++;

        $this->thread->run(
            function () use($params, $counter) {
                $result = 0;

                try {
                    $this->beforeDo($params);

                    $this->do($params);

                    $this->afterDo($params);
                } catch (Exception $exception) {
                    // todo: add logs
                    echo $exception->getMessage() . PHP_EOL;
                    $result = 1;
                }

                return $result;
        });

        $counter++;
    }

    public function wait(): int
    {
        return $this->thread->join();
    }

    public function kill(): int
    {
        return $this->thread->kill();
    }

    public function getName() : ?string
    {
        return $this->name;
    }
}
