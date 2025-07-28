<?php

namespace App\Controller;

use App\Service\TrancheService;

class TrancheController
{
  private TrancheService $trancheService;

  public function __construct(TrancheService $trancheService)
  {
    $this->trancheService = $trancheService;
  }

  public function getAllTranches(): void
  {
    try {
      $tranches = $this->trancheService->recupererTranches();
      header('Content-Type: application/json');
      echo json_encode([
        'data' => array_map(fn($tranche) => $tranche->toArray(), $tranches),
        'statut' => 'success',
        'code' => 200,
        'message' => 'Tranches récupérées'
      ]);
    } catch (\Exception $e) {
      $this->error($e->getMessage(), 500);
    }
  }

  public function createTranche(): void
  {
    try {
      $data = json_decode(file_get_contents('php://input'), true);

      $this->trancheService->ajouterTranche(
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
    } catch (\Exception $e) {
      $this->error($e->getMessage(), 400);
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
