<?php

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/tools/functions.php";

use App\App;

const APP_ROOT = __DIR__;

try {
    (new App())->run($argv);
} catch (Exception $e) {

}
