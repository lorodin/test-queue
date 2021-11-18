<?php

namespace App\Models;

class RabbitInfo
{
    public string $host;
    public int $port;
    public string $user;
    public string $pass;

    public function __construct(string $host, int $port, string $user, string $pass)
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function __toString()
    {
        return "$this->host:$this->port -u $this->user -p *****";
    }
}
