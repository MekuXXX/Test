<?php

require_once __DIR__ . '/Core/Env.php';
require_once __DIR__ . '/Core/Application.php';

new Env(__DIR__ . '/.env');

$app = new Application([
  'user' => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASSWORD'],
  'driver' => $_ENV['DB_DRIVER'],
  'host' => $_ENV['DB_HOST'],
  'port' => $_ENV['DB_PORT'],
  'database' => $_ENV['DB_DATABASE'],
]);


$app->run();
