<?php

namespace App\Services;

use App\Tasks\Task;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;

class TasksManagerService
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function create(string $name):Task
    {
        return $this->container->get("task.$name");
    }
}
