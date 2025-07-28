<?php

// Charger le fichier .env s'il existe, sinon utiliser des valeurs par défaut
try {
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
  $dotenv->load();
} catch (Exception $e) {
  // Utiliser des valeurs par défaut si le fichier .env n'existe pas
  $_ENV['URL'] = $_ENV['URL'] ?? 'http://localhost:8000';
  $_ENV['DB_USER'] = $_ENV['DB_USER'] ?? 'root';
  $_ENV['DB_PASS'] = $_ENV['DB_PASS'] ?? '';
  $_ENV['DSN'] = $_ENV['DSN'] ?? 'mysql:host=localhost;dbname=woyofall;charset=utf8';
  $_ENV['CLOUD_NAME'] = $_ENV['CLOUD_NAME'] ?? 'test';
  $_ENV['API_KEY'] = $_ENV['API_KEY'] ?? 'test';
  $_ENV['API_SECRET'] = $_ENV['API_SECRET'] ?? 'test';
}

define('URL', $_ENV['URL']);
define("USER", $_ENV["DB_USER"]);
define("PASS", $_ENV["DB_PASS"]);
define("DSN", $_ENV["DSN"]);

define("CLOUD_NAME", $_ENV["CLOUD_NAME"]);
define("API_KEY", $_ENV["API_KEY"]);
define("API_SECRET", $_ENV["API_SECRET"]);
