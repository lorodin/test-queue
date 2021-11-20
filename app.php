<?php

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/tools/functions.php";

use App\App;
use App\Bootstrap;
use Dotenv\Dotenv;

const APP_ROOT = __DIR__;

try {
    Dotenv::createImmutable(__DIR__)->load();

    (new App(Bootstrap::load()))->run($argv);
} catch (Exception $e) {

}
