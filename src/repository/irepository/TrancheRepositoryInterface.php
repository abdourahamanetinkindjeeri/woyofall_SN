<?php

namespace App\Repository\IRepository;

use App\Entity\Tranche;

interface TrancheRepositoryInterface
{
  public function insert(Tranche $tranche): void;

  public function selectBy(int $kw): ?Tranche;

  /**
   * @return Tranche[]
   */
  public function selectAll(): array;
}
