<?php

use Dotenv\Dotenv;

// Charger les variables d'environnement depuis le fichier .env
$envPath = '/var/www/.env';
if (file_exists($envPath)) {
  $dotenv = Dotenv::createImmutable('/var/www');
  $dotenv->load();
} else {
  // Essayer le répertoire courant si le répertoire parent n'a pas le fichier
  if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
  } else {
    throw new \Exception('Le fichier .env est requis. Copiez env.example vers .env et configurez vos variables.');
  }
}

return [
  'database' => [
    'host' => $_ENV['DB_HOST'],
    'port' => $_ENV['DB_PORT'],
    'dbname' => $_ENV['DB_NAME'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'dsn' => "pgsql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}",
    'options' => [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]
  ],
  'app' => [
    'env' => $_ENV['APP_ENV'],
    'debug' => $_ENV['APP_DEBUG'] === 'true',
    'url' => $_ENV['APP_URL']
  ]
];
