<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Charger les variables d'environnement si le fichier .env existe
try {
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
  $dotenv->load();
} catch (Exception $e) {
  // Utiliser les variables d'environnement système si .env n'existe pas
  $_ENV['URL'] = $_ENV['APP_URL'] ?? 'http://localhost:8000';
  $_ENV['DB_USER'] = $_ENV['DB_USERNAME'] ?? 'postgres';
  $_ENV['DB_PASS'] = $_ENV['DB_PASSWORD'] ?? '';
  $_ENV['DSN'] = $_ENV['DSN'] ?? 'pgsql:host=localhost;port=5432;dbname=woyofall';
  $_ENV['CLOUD_NAME'] = $_ENV['CLOUD_NAME'] ?? 'test';
  $_ENV['API_KEY'] = $_ENV['API_KEY'] ?? 'test';
  $_ENV['API_SECRET'] = $_ENV['API_SECRET'] ?? 'test';
}

// Charger la configuration
require_once __DIR__ . '/../app/config/env.php';
require_once __DIR__ . '/../app/config/helpers.php';

// Test simple
echo "Test de l'application Woyofall\n";
echo "Variables d'environnement:\n";
echo "URL: " . ($_ENV['URL'] ?? 'non défini') . "\n";
echo "DB_USER: " . ($_ENV['DB_USER'] ?? 'non défini') . "\n";

// Test du conteneur
try {
  $container = App\Core\Container::getInstance();
  echo "✓ Conteneur créé avec succès\n";

  // Test du routeur
  $router = new App\Core\Router($container);
  echo "✓ Routeur créé avec succès\n";

  // Test de l'application
  $app = App\Core\App::getInstance();
  echo "✓ Application créée avec succès\n";

  echo "🎉 Tous les tests sont passés !\n";
} catch (Exception $e) {
  echo "❌ Erreur : " . $e->getMessage() . "\n";
  echo "Fichier : " . $e->getFile() . "\n";
  echo "Ligne : " . $e->getLine() . "\n";
}
