<?php

namespace App\Tasks;

use App\Requests\SendRequest;
use App\Tasks\traits\AsyncTask;
use App\Tasks\traits\RabbitTask;
use App\Utils\Logger\Logger;
use ErrorException;

class ReceiveTask extends Task
{
    use RabbitTask;
    use AsyncTask;

    /**
     * @throws ErrorException
     */
    public function do(array $params)
    {
        $tag = "ReceiveTask" . $this->getName();

        Logger::info($tag, "Task started");

        $this->rabbitMqService->receive(
            function (SendRequest $request) use ($tag) {
                $this->controllersManagerService->callAction(
                    $request->category,
                    $request->task,
                    $request->data
                );
            },
            function (string $messageBody) use ($tag) {
                echo "$tag: Fail to parse message: $messageBody" . PHP_EOL;
            }
        );
    }
}
