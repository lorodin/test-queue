<?php

namespace App\Console;

use App\Console\Exceptions\CommandParserException;

class CommandParser
{
    const HELP_COMMAND    = "help";
    const SEND_COMMAND    = "send";
    const RECEIVE_COMMAND = "receive";

    const VALID_COMMANDS = [self::HELP_COMMAND, self::RECEIVE_COMMAND, self::SEND_COMMAND];

    /**
     * @throws CommandParserException
     */
    public static function getCommand(array $args): string
    {
        if (count($args) < 2)
        {
            throw new CommandParserException("Arguments not exist");
        }

        if (array_search($args[1], self::VALID_COMMANDS) === false) {
            throw new CommandParserException("Unknown command: " . $args[1]);
        }

        return $args[1];
    }

    /**
     * @throws CommandParserException
     */
    public static function getArg(string $key, array $args, bool $strict = false, array $validValues = []): ?string
    {
        if (!isset($key) || $key === "") {
            throw new \InvalidArgumentException();
        }

        $error = function ($errorMessage) use($strict) {
            if ($strict) {
                throw new CommandParserException($errorMessage);
            } else {
                return null;
            }
        };

        foreach ($args as $index => $val) {
            if ($val === $key) {
                if ($index + 1 >= count($args)) {
                    return $error("Value for '" . $key . "' not exist");
                }

                if (array_search($args[$index + 1], $validValues) === false) {
                    return $error("Invalid " . $key . " value: " . $args[$index + 1]);
                }

                return $args[$index + 1];
            }
        }

        return $error("Not found '" . $key . "' argument");
    }
}
