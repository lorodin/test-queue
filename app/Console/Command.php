<?php

namespace App\Console;

use App\App;

abstract class Command
{
    /**
     * @Inject
     * @var App
     */
    protected App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public abstract function do(...$args): int;
}
