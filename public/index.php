<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$autoloadPath = __DIR__.'/../vendor/autoload.php';

if (! file_exists($autoloadPath)) {
    header('Location: ../', true, 302);
    exit;
}

require $autoloadPath;

$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
