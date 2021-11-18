<?php

namespace App\Utils;

class Process
{
    private ?int $pid;

    public function run(callable $callback): int {
        $this->pid = pcntl_fork();

        if ($this->pid) {
            return $this->pid;
        }

        $result = $callback();

        if (is_int($result)) {
            exit($result);
        }

        exit(0);
    }

    public function join() : int {
        pcntl_waitpid($this->pid, $status);
        return $status;
    }

    public function kill() : int {
        posix_kill($this->pid, SIGKILL);
        return $this->join();
    }
}
