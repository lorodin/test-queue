<?php

namespace App\Tasks\traits;

use App\Utils\Logger\Logger;
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

        $this->thread->run(
            function () use($params) {
                $result = Process::RESULT_OK;

                try {
                    $this->beforeDo($params);

                    $this->do($params);

                    $this->afterDo($params);
                } catch (Exception $exception) {
                    Logger::error("AsyncTask{$this->name}", $exception->getMessage());
                    $result = Process::RESULT_ERROR;
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
