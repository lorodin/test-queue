<?php

namespace App\Models;

use Dotenv\Exception\ValidationException;

class Request
{
    private array $data;

    public function __construct(array $request) {
        $this->data = $request;
    }

    public function validate(array $fields) : ?array
    {
        $result = [];

        foreach ($fields as $field) {

            if (!isset($this->data[$field])) {
                throw new ValidationException("Field {$field} not found in request (" . json_encode($this->data) . ")");
            }

            $result[$field] = $this->data[$field];
        }

        return $result;
    }
}
