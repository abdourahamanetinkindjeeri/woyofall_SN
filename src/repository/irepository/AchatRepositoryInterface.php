<?php

namespace App\Repository\IRepository;

use App\Entity\Achat;

interface AchatRepositoryInterface
{
    public function insert(Achat $achat): void;

    public function selectBy(string $reference): ?Achat;

    public function selectAll(): array;

    public function selectsBy(string $numeroCompteur): array;
}
