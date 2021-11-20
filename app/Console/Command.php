<?php

namespace App\Console;

use App\Services\CommandsManagerService;
use App\Utils\Process;
use App\Console\CommandParser;
use Exception;

class Command
{
    /**
     * @Inject
     * @var CommandsManagerService
     */
    protected CommandsManagerService $commandsService;

    public function __construct(CommandsManagerService $commandsService)
    {
        $this->commandsService = $commandsService;
    }

    /**
     * @throws Exceptions\CommandParserException
     * @throws Exception
     */
    public function do(array $args): int
    {
        $result = Process::RESULT_OK;
        $cmd = CommandParser::getCommand($args);

        $this->commandsService->call($cmd, $args);

        return $result;
    }
}
