<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Charger la configuration
require_once __DIR__ . '/../app/config/env.php';
require_once __DIR__ . '/../app/config/helpers.php';

// Démarrer l'application
$app = App\Core\App::getInstance();
$app->run();
