<?php

namespace App\Requests;

/**
 * @property string $category
 * @property string $task
 * @property array $data
 */
class SendRequest extends Request
{
    protected array $validator = ["category", "task", "data"];
}
