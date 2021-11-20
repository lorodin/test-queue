<?php

namespace App\Tasks;

abstract class Task
{
    public function run(array $options) {
        $this->beforeDo($options);

        $this->do($options);

        $this->afterDo($options);
    }

    protected function beforeDo(array $params) {}

    protected function do(array $params) { }

    protected function afterDo(array $params) {}
}
