<?php

namespace App\Service;

use App\Entity\Tranche;
use App\Repository\IRepository\TrancheRepositoryInterface;
use App\Service\IService\TrancheServiceInterface;

class TrancheService implements TrancheServiceInterface
{
  private TrancheRepositoryInterface $trancheRepository;

  public function __construct(TrancheRepositoryInterface $trancheRepository)
  {
    $this->trancheRepository = $trancheRepository;
  }

  public function ajouterTranche(string $nom, int $min, ?int $max, float $prixParKwh): void
  {
    $tranche = new Tranche($nom, $min, $max, $prixParKwh);
    $this->trancheRepository->insert($tranche);
  }

  public function getTranche(int $kw): ?Tranche
  {
    return $this->trancheRepository->selectBy($kw);
  }

  public function recupererTranches(): array
  {
    return $this->trancheRepository->selectAll();
  }
}
