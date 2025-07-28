<?php

namespace App\Migration;

require_once __DIR__ . '/schemas.php';
require_once __DIR__ . '/SQLGenerator.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/env.php';

use PDO;
use App\Migration\SQLGenerator;

function getDatabaseConfig(): array
{
  $env = [
    'DB_HOST' => getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? ''),
    'DB_PORT' => getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? ''),
    'DB_NAME' => getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? ''),
    'DB_USERNAME' => getenv('DB_USERNAME') ?: ($_ENV['DB_USERNAME'] ?? ''),
    'DB_PASSWORD' => getenv('DB_PASSWORD') ?: ($_ENV['DB_PASSWORD'] ?? ''),
  ];

  $host = $env['DB_HOST'] ?: 'localhost';
  $port = $env['DB_PORT'] ?: '5432';
  $dbName = $env['DB_NAME'] ?: '';
  $user = $env['DB_USERNAME'] ?: '';
  $pass = $env['DB_PASSWORD'] ?: '';
  $driver = ($port === '3306') ? 'mysql' : 'pgsql';

  return [
    'DB_HOST' => $host,
    'DB_PORT' => $port,
    'DB_NAME' => $dbName,
    'DB_USERNAME' => $user,
    'DB_PASSWORD' => $pass,
    'DSN' => "$driver:host=$host;port=$port;dbname=$dbName"
  ];
}

// --- PHASE 1 : Récupération des infos
$config = getDatabaseConfig();

$dbName = $config['DB_NAME'];
$user = $config['DB_USERNAME'];
$pass = $config['DB_PASSWORD'];
$host = $config['DB_HOST'];
$port = $config['DB_PORT'];
$driver = ($port === '3306') ? 'mysql' : 'pgsql';
$dsn = $config['DSN'];

// --- PHASE 2 : Connexion à la base existante
try {
  $pdo = new PDO($dsn, $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Utiliser le schéma depuis schemas.php
  $schemas = require __DIR__ . '/schemas.php';

  // Générer et exécuter les requêtes de création de tables
  foreach ($schemas as $table => $columns) {
    $createTableSQL = SQLGenerator::generateCreateTable($table, $columns, $driver);
    echo "Création de la table `$table` :\n$createTableSQL\n";
    $pdo->exec($createTableSQL);
    echo "Table `$table` créée avec succès.\n";
  }

  echo "Toutes les tables ont été créées avec succès.\n";
} catch (\PDOException $e) {
  echo "Erreur PDO : " . $e->getMessage() . "\n";
  exit(1);
}
