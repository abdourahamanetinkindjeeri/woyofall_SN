<?php

namespace App\Controller;

use App\Service\CompteurService;

class CompteurController
{
  private CompteurService $compteurService;

  public function __construct(CompteurService $compteurService)
  {
    $this->compteurService = $compteurService;
  }

  public function create(): void
  {
    $data = json_decode(file_get_contents('php://input'), true);

    try {
      $compteur = $this->compteurService->create($data);

      header('Content-Type: application/json');
      echo json_encode([
        'data' => $compteur->toArray(),
        'statut' => 'success',
        'code' => 201,
        'message' => 'Compteur créé avec succès'
      ]);
    } catch (\Exception $e) {
      header('Content-Type: application/json');
      http_response_code(400);
      echo json_encode([
        'data' => null,
        'statut' => 'error',
        'code' => 400,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function update(string $numero): void
  {
    $data = json_decode(file_get_contents('php://input'), true);

    try {
      $compteur = $this->compteurService->update($numero, $data);

      header('Content-Type: application/json');
      echo json_encode([
        'data' => $compteur->toArray(),
        'statut' => 'success',
        'code' => 200,
        'message' => 'Compteur mis à jour avec succès'
      ]);
    } catch (\Exception $e) {
      header('Content-Type: application/json');
      http_response_code(400);
      echo json_encode([
        'data' => null,
        'statut' => 'error',
        'code' => 400,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function delete(string $numero): void
  {
    try {
      $this->compteurService->delete($numero);

      header('Content-Type: application/json');
      echo json_encode([
        'data' => null,
        'statut' => 'success',
        'code' => 200,
        'message' => 'Compteur supprimé avec succès'
      ]);
    } catch (\Exception $e) {
      header('Content-Type: application/json');
      http_response_code(400);
      echo json_encode([
        'data' => null,
        'statut' => 'error',
        'code' => 400,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function getByNumero(string $numero): void
  {
    try {
      $compteur = $this->compteurService->findByNumero($numero);

      if (!$compteur) {
        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode([
          'data' => null,
          'statut' => 'error',
          'code' => 404,
          'message' => 'Compteur non trouvé'
        ]);
        return;
      }

      header('Content-Type: application/json');
      echo json_encode([
        'data' => $compteur->toArray(),
        'statut' => 'success',
        'code' => 200,
        'message' => 'Compteur trouvé'
      ]);
    } catch (\Exception $e) {
      header('Content-Type: application/json');
      http_response_code(500);
      echo json_encode([
        'data' => null,
        'statut' => 'error',
        'code' => 500,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function getByClientId(int $clientId): void
  {
    try {
      $compteurs = $this->compteurService->findByClientId($clientId);

      header('Content-Type: application/json');
      echo json_encode([
        'data' => array_map(fn($compteur) => $compteur->toArray(), $compteurs),
        'statut' => 'success',
        'code' => 200,
        'message' => 'Compteurs trouvés'
      ]);
    } catch (\Exception $e) {
      header('Content-Type: application/json');
      http_response_code(500);
      echo json_encode([
        'data' => null,
        'statut' => 'error',
        'code' => 500,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function getAll(): void
  {
    try {
      $compteurs = $this->compteurService->getAllCompteurs();

      header('Content-Type: application/json');
      echo json_encode([
        'data' => array_map(fn($compteur) => $compteur->toArray(), $compteurs),
        'statut' => 'success',
        'code' => 200,
        'message' => 'Compteurs récupérés'
      ]);
    } catch (\Exception $e) {
      header('Content-Type: application/json');
      http_response_code(500);
      echo json_encode([
        'data' => null,
        'statut' => 'error',
        'code' => 500,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function updateConsommation(string $numero): void
  {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['kwh'])) {
      header('Content-Type: application/json');
      http_response_code(400);
      echo json_encode([
        'data' => null,
        'statut' => 'error',
        'code' => 400,
        'message' => 'Le paramètre kwh est requis'
      ]);
      return;
    }

    try {
      $this->compteurService->updateConsommation($numero, (float) $data['kwh']);

      header('Content-Type: application/json');
      echo json_encode([
        'data' => null,
        'statut' => 'success',
        'code' => 200,
        'message' => 'Consommation mise à jour avec succès'
      ]);
    } catch (\Exception $e) {
      header('Content-Type: application/json');
      http_response_code(400);
      echo json_encode([
        'data' => null,
        'statut' => 'error',
        'code' => 400,
        'message' => $e->getMessage()
      ]);
    }
  }

  public function resetConsommation(string $numero): void
  {
    try {
      $this->compteurService->resetConsommation($numero);

      header('Content-Type: application/json');
      echo json_encode([
        'data' => null,
        'statut' => 'success',
        'code' => 200,
        'message' => 'Consommation réinitialisée avec succès'
      ]);
    } catch (\Exception $e) {
      header('Content-Type: application/json');
      http_response_code(400);
      echo json_encode([
        'data' => null,
        'statut' => 'error',
        'code' => 400,
        'message' => $e->getMessage()
      ]);
    }
  }
}
