<?php

namespace App\Tasks\traits;

use App\App;
use App\Models\RabbitInfo;
use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

trait RabbitTask
{
    protected AMQPStreamConnection $connection;
    protected AMQPChannel $channel;

    private RabbitInfo $rabbitInfo;

    /**
     * @throws Exception
     */
    public function __construct(App $app, RabbitInfo $rabbitInfo)
    {
        parent::__construct($app);

        $this->rabbitInfo = $rabbitInfo;
    }

    /**
     * @throws Exception
     */
    public function beforeDo(array $params)
    {
        if (!isset($params['queue'])) {
            throw new \InvalidArgumentException("reader or queue not exists");
        }


        $this->connection = new AMQPStreamConnection($this->rabbitInfo->host,
                                                     $this->rabbitInfo->port,
                                                     $this->rabbitInfo->user,
                                                     $this->rabbitInfo->pass);

        if (!$this->connection->isConnected()) {
            throw new Exception("Fail to open rabbit connection (" . $this->rabbitInfo . ")");
        }

        $this->channel = $this->connection->channel();

        $this->channel->queue_declare($params['queue'], false, false, false, false);
    }

    /**
     * @throws Exception
     */
    public function afterDo(array $params)
    {
        $this->channel->close();
        $this->connection->close();
    }
}
