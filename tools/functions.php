<?php

require_once __DIR__ . "/../configs/config.php";

function env($key, $defaultValue = null) {
    return $_ENV[$key] ?? $defaultValue;
}

function config($key, $defaultValue = null) {
    return CONFIG[$key] ?? $defaultValue;
}

