<?php

namespace App;

use App\Console\Exceptions\CommandParserException;
use App\Console\CommandParser;
use App\Models\Request;
use App\Tasks\Task;
use App\Utils\Logger\ILogger;
use App\Utils\Logger\Logger;
use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Dotenv\Dotenv;
use Dotenv\Exception\ValidationException;
use Exception;
use InvalidArgumentException;

class App
{
    const TAG = App::class;

    protected Container $container;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(APP_ROOT);
        $dotenv->load();

        $builder = new ContainerBuilder();

        $builder->addDefinitions(APP_ROOT . "/configs/core.php");

        $builder->addDefinitions(APP_ROOT . "/configs/console.php");

        $builder->addDefinitions(APP_ROOT . "/configs/controllers.php");

        $builder->addDefinitions(APP_ROOT . "/configs/middlewares.php");

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

        if (!$this->container->has(ILogger::class)) {
            echo "ILogger not created" . PHP_EOL;
            exit(1);
        }

        Logger::init($this->container->get(ILogger::class));
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
        } catch (InvalidArgumentException | Exception $exception) {
            Logger::error(App::class, $exception->getMessage());
        }

        exit($result);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function getTask($type): ?Task
    {
        return $this->container->get($type);
    }

    /**
     * @param string $controller
     * @param string $action
     * @param Request $request
     * @return bool
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ValidationException
     */
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
