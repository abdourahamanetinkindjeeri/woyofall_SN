<?php

namespace App\Controller;

use App\Controller\IController\AchatControllerInterface;
use App\Service\IService\AchatServiceInterface;
use App\Service\IService\ClientServiceInterface;
use App\Service\IService\TrancheServiceInterface;

class AchatController implements AchatControllerInterface
{
  private AchatServiceInterface $achatService;
  private ClientServiceInterface $clientService;
  private TrancheServiceInterface $trancheService;

  public function __construct(
    AchatServiceInterface $achatService,
    ClientServiceInterface $clientService,
    TrancheServiceInterface $trancheService
  ) {
    $this->achatService = $achatService;
    $this->clientService = $clientService;
    $this->trancheService = $trancheService;
  }

  public function acheter(): void
  {
    header('Content-Type: application/json');

    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['numero_compteur'], $input['montant'], $input['client_id'])) {
      http_response_code(400);
      echo json_encode([
        'data' => null,
        'statut' => 'error',
        'code' => 400,
        'message' => 'Les champs numero_compteur, montant et client_id sont obligatoires'
      ]);
      return;
    }

    try {
      $client = $this->clientService->recupererClient($input['client_id']);
      if (!$client) {
        throw new \Exception("Client non trouvé");
      }

      $compteur = new \App\Entity\Compteur($input['numero_compteur']);
      $tranches = $this->trancheService->recupererTranches();

      $achat = $this->achatService->effectuerAchat(
        $input['numero_compteur'],
        (float)$input['montant'],
        $client,
        $compteur,
        $tranches
      );

      http_response_code(200);
      echo json_encode([
        'data' => [
          'reference' => $achat->getReference(),
          'code_recharge' => $achat->getCodeRecharge(),
          'nbre_kwt' => $achat->getNbreKwt(),
          'date' => $achat->getDate()->format('Y-m-d H:i:s'),
          'montant' => $achat->getMontant(),
          'tranche' => $achat->getTranche()->getNom(),
          'compteur' => $achat->getCompteur()->getNumero(),
          'client_id' => $achat->getClient()->getId()
        ],
        'statut' => 'success',
        'code' => 200,
        'message' => 'Achat effectué avec succès'
      ]);
    } catch (\Exception $e) {
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
