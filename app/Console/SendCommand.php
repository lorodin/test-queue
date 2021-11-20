<?php

namespace App\Console;

use App\Readers\DebugReader;
use App\Readers\JsonReader;
use App\Requests\SendRequest;
use App\Services\RabbitMqApiService;
use Exception;
use InvalidArgumentException;

class SendCommand
{
    private RabbitMqApiService $rabbitMqService;

    public function __construct(RabbitMqApiService $rabbitMqService)
    {
        $this->rabbitMqService = $rabbitMqService;
    }

    /**
     * @throws Exception
     */
    public function do(...$args) : int
    {
        if (count($args) == 0) {
            throw new InvalidArgumentException("Not set sending resource");
        }

        $type = $args[0];

        if ($type != "message" && $type != "json") {
            throw new InvalidArgumentException("Unknown type: $type");
        }

        $reader = $type == "message" ? new DebugReader($args[1]) : new JsonReader($args[1]);

        $this->rabbitMqService->connect(env("RABBIT_QUEUE", "example_queue"));

        foreach ($reader->read() as $message) {
            $request = new SendRequest($message);
            $request->validate();
            $this->rabbitMqService->send($request);
            echo "Send message " . json_encode($message) . " successfully" . PHP_EOL;
        }

        $this->rabbitMqService->close();

        return 0;
    }
}
