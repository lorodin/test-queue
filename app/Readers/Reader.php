<?php

namespace App\Readers;

use Generator;

abstract class Reader
{
    protected string $data;

    public function __construct(string $data) {
        $this->data = $data;
    }

    public abstract function read(): Generator;
}
