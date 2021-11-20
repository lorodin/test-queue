<?php

namespace App\Services;

use App\Models\RabbitInfo;
use App\Requests\Request;
use App\Requests\SendRequest;
use App\Utils\Logger\Logger;
use Dotenv\Exception\ValidationException;
use ErrorException;
use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMqApiService
{
    protected string $queue;

    private RabbitInfo $rabbitInfo;
    private ?AMQPStreamConnection $connection;
    private AMQPChannel $channel;

    public function __construct(?RabbitInfo $rabbitInfo)
    {
        $this->rabbitInfo =
            $rabbitInfo ??
            new RabbitInfo(
                env("RABBIT_HOST", "localhost"),
                env("RABBIT_PORT", 5672),
                env("RABBIT_USER", "guest"),
                env("RABBIT_PASS", "guest")
            );

        $this->connection = null;
    }

    public function send(SendRequest $request) : bool
    {
        if (!$this->connected()) {
            return false;
        }

        $msg = new AMQPMessage(
            $request->encode(),
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );

        $this->channel->basic_publish($msg, '', $this->queue);

        return true;
    }

    /**
     * @throws ErrorException
     */
    public function receive(callable $cbSuccess, callable $cbFail = null)
    {
        $this->channel->basic_qos(
            null,
            1,
            null
        );

        $this->channel->basic_consume(
            $this->queue,
            '',
            false,
            false,
            false,
            false,
            function (AMQPMessage $msg) use($cbSuccess, $cbFail) {
                try {
                    $request = new SendRequest(json_decode($msg->body, true));

                    $request->validate();

                    $cbSuccess($request);
                } catch (ValidationException $exception) {
                    Logger::error(RabbitMqApiService::class, $exception->getMessage());

                    if ($cbFail) {
                        $cbFail($msg->body);
                    }
                }

                $msg->ack();
            }
        );

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    /**
     * @throws Exception
     */
    public function connect(string $queue)
    {
        if ($this->connected()) {
            return;
        }

        if (!isset($queue) || $queue == "") {
            throw new \InvalidArgumentException("channel name not set");
        }

        $this->queue = $queue;

        $this->connection = new AMQPStreamConnection(
            $this->rabbitInfo->host,
            $this->rabbitInfo->port,
            $this->rabbitInfo->user,
            $this->rabbitInfo->pass
        );

        if (!$this->connection->isConnected()) {
            throw new Exception("Fail to open rabbit connection (" . $this->rabbitInfo . ")");
        }

        $this->channel = $this->connection->channel();

        $this->channel->queue_declare($queue, false, false, false, false);
    }

    public function connected() : bool
    {
        return $this->connection && $this->connection->isConnected();
    }

    /**
     * @throws Exception
     */
    public function close()
    {
        if ($this->connected())
        {
            $this->channel->close();
            $this->connection->close();
        }
    }
}
