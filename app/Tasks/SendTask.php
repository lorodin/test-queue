<?php

namespace App\Tasks;

use App\Readers\Reader;
use App\Tasks\traits\RabbitTask;
use Exception;
use PhpAmqpLib\Message\AMQPMessage;

class SendTask extends Task
{
    use RabbitTask;

    /**
     * @throws Exception
     */
    public function do(array $params)
    {
        if (!isset($params['reader']) || !($params['reader'] instanceof Reader)) {
            throw new \InvalidArgumentException("reader or queue not exists");
        }

        $reader = $params['reader'];

        foreach ($reader->read() as $message) {
            $msg = new AMQPMessage(
                json_encode($message),
                ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
            );

            $this->channel->basic_publish($msg, '', $params['queue']);
        }

        $this->channel->close();
        $this->connection->close();
    }
}
