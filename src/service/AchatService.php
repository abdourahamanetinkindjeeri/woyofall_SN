<?php

namespace App\Service;

use App\Entity\Achat;
use App\Entity\Client;
use App\Entity\Compteur;
use App\Repository\IRepository\AchatRepositoryInterface;
use App\Service\IService\AchatServiceInterface;
use App\Service\IService\CompteurServiceInterface;
use Exception;

class AchatService implements AchatServiceInterface
{
  private AchatRepositoryInterface $achatRepository;
  private CompteurServiceInterface $compteurService;

  public function __construct(AchatRepositoryInterface $achatRepository, CompteurServiceInterface $compteurService)
  {
    $this->achatRepository = $achatRepository;
    $this->compteurService = $compteurService;
  }

  public function effectuerAchat(
    string $numeroCompteur,
    float $montant,
    Client $client,
    Compteur $compteur,
    array $tranches
  ): Achat {
    if (empty($numeroCompteur)) {
      throw new Exception("Le numéro de compteur est obligatoire.");
    }
    if ($montant <= 0) {
      throw new Exception("Le montant doit être supérieur à 0.");
    }

    $nbreKwt = $this->calculerKwhSelonTranches($montant, $tranches);

    $reference = uniqid('ACH-');
    $codeRecharge = $this->genererCodeRecharge();

    $date = new \DateTimeImmutable();

    $trancheCorrespondante = null;
    foreach ($tranches as $tranche) {
      if ($tranche->contient((int)$nbreKwt)) {
        $trancheCorrespondante = $tranche;
        break;
      }
    }
    if (!$trancheCorrespondante) {
      throw new Exception("Aucune tranche valide trouvée pour la consommation calculée.");
    }

    $achat = new Achat(
      reference: $reference,
      codeRecharge: $codeRecharge,
      nbreKwt: $nbreKwt,
      date: $date,
      tranche: $trancheCorrespondante,
      montant: $montant,
      compteur: $compteur,
      client: $client
    );

    $this->achatRepository->insert($achat);

    // Mettre à jour la consommation du compteur
    $this->compteurService->updateConsommation($numeroCompteur, $nbreKwt);

    return $achat;
  }

  private function calculerKwhSelonTranches(float $montant, array $tranches): float
  {
    $prixBase = $tranches[0]->getPrixParKwh();
    return $montant / $prixBase;
  }

  private function genererCodeRecharge(): string
  {
    $segments = [];
    $segmentLength = 4;
    $numSegments = 5; // 20 chiffres = 5 segments de 4 chiffres

    for ($i = 0; $i < $numSegments; $i++) {
      // Générer un segment de 4 chiffres aléatoires, en ajoutant des zéros à gauche si besoin
      $segment = str_pad((string)random_int(0, 9999), $segmentLength, '0', STR_PAD_LEFT);
      $segments[] = $segment;
    }

    // Retourner le code au format "1234-5678-9012-3456-7890"
    return implode('-', $segments);
  }
}
