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

    /**
     * @throws ErrorException
     */
    public function do(array $params)
    {
        $this->channel->basic_consume(
            $params['queue'],
            '',
            false,
            false,
            false,
            false,
            function (AMQPMessage $msg) { $this->process($msg); } );

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    private function process(AMQPMessage $msg) {
        $rawRequest = new Request(json_decode($msg->body, true));
        $request = $rawRequest->validate(['category', 'task', 'data']);
        if (isset($request) && count($request) != 0) {
            $action = $this->app->callAction(
                $request['category'],
                $request['task'],
                new Request($request['data'])
            );

            if (!$action) {
                // todo: Добавить лог об ошибке
                echo "ERROR" . PHP_EOL;
            }
        } else {
            // todo: Добавить лог об ошибке
            echo "Error request" . PHP_EOL;
        }
    }
}
