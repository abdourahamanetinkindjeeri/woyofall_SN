<?php

namespace App\Service\IService;

use App\Entity\Tranche;

interface TrancheServiceInterface
{
  public function ajouterTranche(string $nom, int $min, ?int $max, float $prixParKwh): void;

  public function getTranche(int $kw): ?Tranche;

  /**
   * @return Tranche[]
   */
  public function recupererTranches(): array;
}
