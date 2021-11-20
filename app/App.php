<?php

namespace App;

use App\Console\Command;
use App\Console\Exceptions\CommandParserException;
use App\Console\CommandParser;
use App\Utils\Logger\Logger;
use DI\Container;
use Exception;
use InvalidArgumentException;

class App
{
    protected Container $container;

    /**
     * @throws Exception
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function run(array $args)
    {
        $result = 1;

        $cli = $this->container->get(Command::class);

        try {
            $result = $cli->do($args);
        } catch (CommandParserException $exception) {
            echo "Command is incorrect: " . $exception->getMessage() . PHP_EOL;
            $result = $cli->do([$args[0], 'help']);
        } catch (InvalidArgumentException | Exception $exception) {
            Logger::error(App::class, $exception->getMessage());
        }

        exit($result);
    }
}
