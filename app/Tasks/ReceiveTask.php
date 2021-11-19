<?php

namespace App\Tasks;

use App\Models\Request;
use App\Tasks\traits\AsyncTask;
use App\Tasks\traits\RabbitTask;
use ErrorException;
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

        $this->app
            ->getLogger()
            ->logI($this->TAG, "Task started");

        $this->channel->basic_consume(
            $params['queue'],
            '',
            false,
            true,
            false,
            false,
            function (AMQPMessage $msg) { $this->process($msg); } );

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    private function process(AMQPMessage $msg) {
        $logger = $this->app->getLogger();

        $rawRequest = new Request(json_decode($msg->body, true));
        $request = $rawRequest->validate(['category', 'task', 'data']);

        if (isset($request) && count($request) != 0) {
            $action = $this->app->callAction(
                $request['category'],
                $request['task'],
                new Request($request['data'])
            );

            if (!$action) {
                $logger->logE($this->TAG, "Bad request. Unknown action: " . json_encode($request));
            }
        } else {
            $logger->logE($this->TAG, "Fail to parse request body: {$msg->body}");
        }
    }
}
