<?php

namespace App\Requests;

use Dotenv\Exception\ValidationException;

class Request
{
    protected array $validator = [];
    private array $rawData;

    public function __construct(array $rawData) {
        $this->rawData = $rawData;
    }

    public function validate(?array $validator = null)
    {
        $validator = $validator ?? $this->validator;

        foreach ($validator as $field) {
            if (!isset($this->rawData[$field])) {
                throw new ValidationException("Field {$field} not found in request (" . json_encode($this->rawData) . ")");
            }
            $this->{$field} = $this->rawData[$field];
        }
    }

    public function encode(): string
    {
        return json_encode($this->rawData);
    }
}
