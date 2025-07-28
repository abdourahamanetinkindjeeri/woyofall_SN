<?php

namespace App\Controller;

use App\Service\ClientService;

class ClientController
{
  private ClientService $clientService;

  public function __construct(ClientService $clientService)
  {
    $this->clientService = $clientService;
  }

  public function getAllClients(): void
  {
    try {
      $clients = $this->clientService->getAllClients();
      header('Content-Type: application/json');
      echo json_encode([
        'data' => array_map(fn($client) => $client->toArray(), $clients),
        'statut' => 'success',
        'code' => 200,
        'message' => 'Clients récupérés'
      ]);
    } catch (\Exception $e) {
      $this->error($e->getMessage(), 500);
    }
  }

  public function createClient(): void
  {
    try {
      $data = json_decode(file_get_contents('php://input'), true);
      $client = $this->clientService->create($data);

      header('Content-Type: application/json');
      echo json_encode([
        'data' => $client->toArray(),
        'statut' => 'success',
        'code' => 200,
        'message' => 'Client créé avec succès'
      ]);
    } catch (\Exception $e) {
      $this->error($e->getMessage(), 400);
    }
  }

  public function getClientById(string $id): void
  {
    try {
      $clientId = (int) $id;
      $client = $this->clientService->recupererClient($clientId);

      if (!$client) {
        $this->error('Client non trouvé', 404);
        return;
      }

      header('Content-Type: application/json');
      echo json_encode([
        'data' => $client->toArray(),
        'statut' => 'success',
        'code' => 200,
        'message' => 'Client trouvé'
      ]);
    } catch (\Exception $e) {
      $this->error($e->getMessage(), 500);
    }
  }

  private function error(string $message, int $code = 400): void
  {
    header('Content-Type: application/json');
    http_response_code($code);
    echo json_encode([
      'data' => null,
      'statut' => 'error',
      'code' => $code,
      'message' => $message
    ]);
  }
}
