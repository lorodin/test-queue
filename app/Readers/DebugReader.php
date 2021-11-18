<?php

namespace App\Readers;

use Generator;

class DebugReader extends Reader
{
    public function read(): Generator
    {
        yield ["category" =>  "debug", "task" => "log", "data" => ["message" => $this->data]];
    }
}
