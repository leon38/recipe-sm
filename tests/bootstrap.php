<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

$_SERVER['APP_ENV'] ??= 'test';
$_ENV['APP_ENV'] ??= 'test';

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

dump([
    'APP_ENV_SERVER' => $_SERVER['APP_ENV'] ?? null,
    'APP_ENV_ENV' => $_ENV['APP_ENV'] ?? null,
    'DATABASE_URL_SERVER' => $_SERVER['DATABASE_URL'] ?? null,
    'DATABASE_URL_ENV' => $_ENV['DATABASE_URL'] ?? null,
]);

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}