<?php

namespace App;

use App\Console\Command;
use App\Console\Exceptions\CommandParserException;
use App\Console\CommandParser;
use App\Models\RabbitInfo;
use App\Models\Request;
use App\Tasks\Task;
use App\Tasks\traits\RabbitTask;
use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Exception;
use InvalidArgumentException;

class App
{
    protected Container $container;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(APP_ROOT);
        $dotenv->load();

        $builder = new ContainerBuilder();

        $builder->addDefinitions(APP_ROOT . "/di/core.php");
        $builder->addDefinitions(APP_ROOT . "/di/console.php");
        $builder->addDefinitions(APP_ROOT . "/di/controllers.php");
        $builder->addDefinitions(APP_ROOT . "/di/middlewares.php");

        $builder->addDefinitions([
            App::class => function (Container $c) {
                return $this;
            }
        ]);

        $this->container = $builder->build();

        if (!$this->container->has("console.help"))
        {
            echo "Help command not found" . PHP_EOL;
            exit(1);
        }
    }

    public function run(array $args)
    {
        $result = 1;

        try {
            $cmd = "console." . CommandParser::getCommand($args);

            if (!$this->container->has($cmd)) {
                throw new CommandParserException("Unknown command");
            }

            $result = $this->container->call(
                $this->container->get($cmd),
                $args
            );
        } catch (CommandParserException $exception) {
            echo "Command is incorrect: " . $exception->getMessage() . PHP_EOL;
            $this->container->call(
                $this->container->get("console.Help"),
                $args
            );
        } catch (InvalidArgumentException $exception) {
            // todo: Добавить логи в файл
            echo "Application error =(" . PHP_EOL;
        } catch (Exception $exception) {
            // todo: Добавить логи в файл
            echo "Rabbit connection is failure" . PHP_EOL;
        }

        exit($result);
    }

    public function getTask($type): ?Task
    {
        return $this->container->get($type);
    }

    public function callAction(string $controller, string $action, Request $request): bool
    {
        $middleware = "middleware.{$controller}.{$action}";
        $cAction = "controller.{$controller}.{$action}";

        if (!$this->container->has($cAction)) {
            return false;
        }

        if ($this->container->has($middleware)) {
            $this->container->call(
                $this->container->get($middleware),
                [
                    'request' => $request,
                    'next'    => $this->container->get($cAction)
                ]
            );
        } else {
            $this->container->call(
                $this->container->get($cAction),
                [
                    'request' => $request
                ]
            );
        }

        return true;
    }
}
