<?php

require_once __DIR__ . '/vendor/autoload.php';

// Charger les vraies variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Configuration pour app/config/env.php
$_ENV['URL'] = $_ENV['APP_URL'];
$_ENV['DB_USER'] = $_ENV['DB_USERNAME'];
$_ENV['DB_PASS'] = $_ENV['DB_PASSWORD'];
$_ENV['DSN'] = "pgsql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}";
$_ENV['CLOUD_NAME'] = $_ENV['CLOUD_NAME'] ?? 'test';
$_ENV['API_KEY'] = $_ENV['API_KEY'] ?? 'test';
$_ENV['API_SECRET'] = $_ENV['API_SECRET'] ?? 'test';

// Charger la configuration
require_once __DIR__ . '/app/config/env.php';
require_once __DIR__ . '/app/config/helpers.php';

try {
  echo "Test de la nouvelle structure...\n";

  // Test du conteneur
  $container = App\Core\Container::getInstance();
  echo "✓ Conteneur créé avec succès\n";

  // Test du routeur
  $router = $container->get('Router');
  echo "✓ Routeur créé avec succès\n";

  // Test de l'application
  $app = App\Core\App::getInstance();
  echo "✓ Application créée avec succès\n";

  // Test des services
  $clientService = $container->get('ClientService');
  echo "✓ ClientService créé avec succès\n";

  $compteurService = $container->get('CompteurService');
  echo "✓ CompteurService créé avec succès\n";

  $achatService = $container->get('AchatService');
  echo "✓ AchatService créé avec succès\n";

  $trancheService = $container->get('TrancheService');
  echo "✓ TrancheService créé avec succès\n";

  // Test des contrôleurs
  $clientController = $container->get('ClientController');
  echo "✓ ClientController créé avec succès\n";

  $compteurController = $container->get('CompteurController');
  echo "✓ CompteurController créé avec succès\n";

  $achatController = $container->get('AchatController');
  echo "✓ AchatController créé avec succès\n";

  $trancheController = $container->get('TrancheController');
  echo "✓ TrancheController créé avec succès\n";

  echo "\n🎉 Tous les tests sont passés ! La nouvelle structure fonctionne correctement.\n";
} catch (Exception $e) {
  echo "❌ Erreur : " . $e->getMessage() . "\n";
  echo "Fichier : " . $e->getFile() . "\n";
  echo "Ligne : " . $e->getLine() . "\n";
}
