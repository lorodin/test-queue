<?php

namespace App\Utils\Logger;

interface Logger
{
    function logI(string $tag, string $message);
    function logD(string $tag, string $message);
    function logE(string $tag, string $message);
}
