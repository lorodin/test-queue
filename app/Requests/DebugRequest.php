<?php

namespace App\Requests;

/**
 * @property string $message
 */
class DebugRequest extends Request
{
    protected array $validator = ["message"];
}
