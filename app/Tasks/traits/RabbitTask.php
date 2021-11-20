<?php

namespace App\Tasks\traits;

use App\Services\ControllersManagerService;
use App\Services\RabbitMqApiService;
use Exception;

trait RabbitTask
{
    protected RabbitMqApiService $rabbitMqService;
    protected ControllersManagerService $controllersManagerService;

    /**
     * @throws Exception
     */
    public function __construct(RabbitMqApiService $rabbitMqService, ControllersManagerService $controllersManagerService)
    {
        $this->rabbitMqService = $rabbitMqService;
        $this->controllersManagerService = $controllersManagerService;
    }

    /**
     * @throws Exception
     */
    public function beforeDo(array $params)
    {
        if (!isset($params['queue'])) {
            throw new \InvalidArgumentException("reader or queue not exists");
        }

        $this->rabbitMqService->connect($params['queue']);
    }

    /**
     * @throws Exception
     */
    public function afterDo(array $params)
    {
        $this->rabbitMqService->close();
    }
}
