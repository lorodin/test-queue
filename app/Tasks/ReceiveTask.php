<?php

namespace App\Tasks;

use App\Models\Request;
use App\Tasks\traits\AsyncTask;
use App\Tasks\traits\RabbitTask;
use App\Utils\Logger\Logger;
use Dotenv\Exception\ValidationException;
use ErrorException;
use Exception;
use PhpAmqpLib\Message\AMQPMessage;

class ReceiveTask extends Task
{
    use RabbitTask;
    use AsyncTask;

    private string $TAG;

    /**
     * @throws ErrorException
     */
    public function do(array $params)
    {
        $this->TAG = "ReceiveTask" . $this->getName();

        Logger::info($this->TAG, "Task started");

        $this->channel->basic_qos(
            null,
            1,
            null
        );

        $this->channel->basic_consume(
            $params['queue'],
            '',
            false,
            false,
            false,
            false,
            function (AMQPMessage $msg) {
                try {
                    $rawRequest = new Request(json_decode($msg->body, true));
                    $request = $rawRequest->validate(['category', 'task', 'data']);

                    $this->send($request['category'], $request['task'], $request['data']);
                } catch (ValidationException $exception) {
                    Logger::error($this->TAG, $exception->getMessage());
                }

                $msg->ack();
            }
        );

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    private function send(string $controller, string $action, array $body) {
        try {
            $action = $this->app->callAction(
                $controller,
                $action,
                new Request($body)
            );

            if (!$action) {
                Logger::error($this->TAG, "Bad request. Unknown action: " . json_encode($body));
            }
        } catch (Exception $exception) {
            Logger::error($this->TAG, $exception->getMessage());
        }
    }
}
