<?php

function env($key, $defaultValue = null) {
    return $_ENV[$key] ?? $defaultValue;
}

