<?php

namespace App\Services;

use DI\Container;

class CommandsManagerService
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @throws \Exception
     */
    public function call(string $cmd, array $args)
    {
        $cmd = "console.$cmd";

        if (!$this->container->has($cmd)) {
            throw new \Exception("Command `$cmd` not found");
        }

        $cmdArgs = array_slice($args, 3, count($args));

        $this->container->call(
            [$this->container->make($cmd), "do"],
            $cmdArgs
        );
    }
}
