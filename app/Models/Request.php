<?php

namespace App\Models;

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
                return null;
            }

            $result[$field] = $this->data[$field];
        }

        return $result;
    }
}
