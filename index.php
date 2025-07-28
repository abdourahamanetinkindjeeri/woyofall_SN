<?php

use App\Repository\AchatRepository;
use App\Repository\ClientRepository;
use App\Repository\TrancheRepository;
use App\Repository\CompteurRepository;
use App\Service\AchatService;
use App\Service\ClientService;
use App\Service\TrancheService;
use App\Service\CompteurService;
use App\Controller\AchatController;
use App\Controller\CompteurController;

require_once __DIR__ . '/vendor/autoload.php';

// Configuration de la base de données
$config = require __DIR__ . '/config/database.php';
$pdo = new PDO($config['dsn'], $config['username'], $config['password'], $config['options']);

// Initialisation des repositories
$clientRepository = new ClientRepository($pdo);
$trancheRepository = new TrancheRepository($pdo);
$achatRepository = new AchatRepository($pdo);
$compteurRepository = new CompteurRepository($pdo);

// Initialisation des services
$clientService = new ClientService($clientRepository);
$trancheService = new TrancheService($trancheRepository);
$compteurService = new CompteurService($compteurRepository);
$achatService = new AchatService($achatRepository, $compteurService);

// Initialisation des contrôleurs
$achatController = new AchatController($achatService, $clientService, $trancheService);
$compteurController = new CompteurController($compteurService);

// Gestion des routes
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');
$method = $_SERVER['REQUEST_METHOD'];

// Route pour l'achat
if ($uri === 'api/achat' && $method === 'POST') {
  $achatController->acheter();
  exit;
}

// Routes pour les clients
if ($uri === 'api/client' && $method === 'GET') {
  // Récupérer tous les clients
  $clients = $clientService->getAllClients();
  header('Content-Type: application/json');
  echo json_encode([
    'data' => array_map(fn($client) => $client->toArray(), $clients),
    'statut' => 'success',
    'code' => 200,
    'message' => 'Clients récupérés'
  ]);
  exit;
}

if ($uri === 'api/client' && $method === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  try {
    $client = $clientService->create($data);
    header('Content-Type: application/json');
    echo json_encode([
      'data' => $client->toArray(),
      'statut' => 'success',
      'code' => 200,
      'message' => 'Client créé avec succès'
    ]);
  } catch (Exception $e) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode([
      'data' => null,
      'statut' => 'error',
      'code' => 400,
      'message' => $e->getMessage()
    ]);
  }
  exit;
}

if (preg_match('/^api\/client\/(\d+)$/', $uri, $matches)) {
  $clientId = (int) $matches[1];

  if ($method === 'GET') {
    $client = $clientService->recupererClient($clientId);
    if (!$client) {
      header('Content-Type: application/json');
      http_response_code(404);
      echo json_encode([
        'data' => null,
        'statut' => 'error',
        'code' => 404,
        'message' => 'Client non trouvé'
      ]);
      exit;
    }

    header('Content-Type: application/json');
    echo json_encode([
      'data' => $client->toArray(),
      'statut' => 'success',
      'code' => 200,
      'message' => 'Client trouvé'
    ]);
    exit;
  }
}

// Routes pour les tranches
if ($uri === 'api/tranche') {
  if ($method === 'GET') {
    // Récupérer toutes les tranches
    $tranches = $trancheService->recupererTranches();
    header('Content-Type: application/json');
    echo json_encode([
      'data' => array_map(fn($tranche) => $tranche->toArray(), $tranches),
      'statut' => 'success',
      'code' => 200,
      'message' => 'Tranches récupérées'
    ]);
    exit;
  } elseif ($method === 'POST') {
    // Ajouter une nouvelle tranche
    $data = json_decode(file_get_contents('php://input'), true);
    try {
      $trancheService->ajouterTranche(
        $data['nom'],
        (int)$data['min'],
        isset($data['max']) ? (int)$data['max'] : null,
        (float)$data['prix_par_kwh']
      );
      header('Content-Type: application/json');
      echo json_encode([
        'data' => null,
        'statut' => 'success',
        'code' => 200,
        'message' => 'Tranche ajoutée avec succès'
      ]);
    } catch (Exception $e) {
      header('Content-Type: application/json');
      http_response_code(400);
      echo json_encode([
        'data' => null,
        'statut' => 'error',
        'code' => 400,
        'message' => $e->getMessage()
      ]);
    }
    exit;
  }
}

// Routes pour les compteurs
if (preg_match('/^api\/compteur$/', $uri)) {
  if ($method === 'POST') {
    $compteurController->create();
    exit;
  } elseif ($method === 'GET') {
    $compteurController->getAll();
    exit;
  }
}

if (preg_match('/^api\/compteur\/(.+)$/', $uri, $matches)) {
  $numero = $matches[1];

  if ($method === 'GET') {
    $compteurController->getByNumero($numero);
    exit;
  } elseif ($method === 'PUT') {
    $compteurController->update($numero);
    exit;
  } elseif ($method === 'DELETE') {
    $compteurController->delete($numero);
    exit;
  }
}

if (preg_match('/^api\/compteur\/client\/(\d+)$/', $uri, $matches)) {
  if ($method === 'GET') {
    $clientId = (int) $matches[1];
    $compteurController->getByClientId($clientId);
    exit;
  }
}

if (preg_match('/^api\/compteur\/(.+)\/consommation$/', $uri, $matches)) {
  $numero = $matches[1];

  if ($method === 'PUT') {
    $compteurController->updateConsommation($numero);
    exit;
  }
}

if (preg_match('/^api\/compteur\/(.+)\/reset$/', $uri, $matches)) {
  $numero = $matches[1];

  if ($method === 'POST') {
    $compteurController->resetConsommation($numero);
    exit;
  }
}

// Routes pour les achats
if ($uri === 'api/achat') {
  if ($method === 'GET') {
    // Récupérer tous les achats
    $achats = $achatService->getAllAchats();
    header('Content-Type: application/json');
    echo json_encode([
      'data' => array_map(fn($achat) => $achat->toArray(), $achats),
      'statut' => 'success',
      'code' => 200,
      'message' => 'Achats récupérés'
    ]);
    exit;
  } elseif ($method === 'POST') {
    // Effectuer un achat
    $achatController->acheter();
    exit;
  }
}

// Route par défaut
header('Content-Type: application/json');
http_response_code(404);
echo json_encode([
  'data' => null,
  'statut' => 'error',
  'code' => 404,
  'message' => 'Route non trouvée'
]);
