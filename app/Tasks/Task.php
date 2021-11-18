<?php

namespace App\Tasks;

use App\App;

abstract class Task
{
    /**
     * @Inject
     * @var App
     */
    protected App $app;

    public function __construct(App $app) {
        $this->app = $app;
    }

    public function run(array $options) {
        $this->beforeDo($options);

        $this->do($options);

        $this->afterDo($options);
    }

    protected function beforeDo(array $params) {}

    protected function do(array $params) { }

    protected function afterDo(array $params) {}
}
