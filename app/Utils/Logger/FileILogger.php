<?php

namespace App\Utils\Logger;

class FileILogger implements ILogger
{
    private string $logsDir;

    public function __construct(string $logsDir)
    {
        $this->logsDir = $logsDir;

        if (!file_exists($this->logsDir)) {
            mkdir($this->logsDir, 0755, true);
        }
    }

    public function logI(string $tag, string $message)
    {
        $date = new \DateTime('now');
        $this->write("[{$date->format("Y-m-d H:i:s.u")}][{$tag}][INFO] $message" . PHP_EOL);
    }

    public function logD(string $tag, string $message)
    {
        $date = new \DateTime('now');
        $this->write("[{$date->format("Y-m-d H:i:s.u")}][{$tag}][DEBUG] $message" . PHP_EOL);
    }

    public function logE(string $tag, string $message)
    {
        $date = new \DateTime('now');
        $this->write("[{$date->format("Y-m-d H:i:s.u")}][{$tag}][ERROR] $message" . PHP_EOL);
    }

    private function write(string $message)
    {
        file_put_contents("{$this->logsDir}/logs.log", $message, FILE_APPEND);
    }
}
