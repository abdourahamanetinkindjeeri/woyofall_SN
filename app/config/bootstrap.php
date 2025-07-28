<?php

// Charger l'autoloader de Composer
require_once __DIR__ . '/../../vendor/autoload.php';

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Charger la configuration
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/helpers.php';
