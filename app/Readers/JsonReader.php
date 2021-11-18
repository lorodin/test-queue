<?php

namespace App\Readers;

use Exception;
use Generator;

class JsonReader extends Reader
{
    /**
     * @throws Exception
     */
    public function read(): Generator
    {
        if (!file_exists($this->data)) {
            throw new Exception("File " . $this->data . " not found");
        }

        $json = file_get_contents($this->data);
        $data = json_decode($json, true);

        foreach ($data as $message) {
            yield $message;
        }
    }
}
