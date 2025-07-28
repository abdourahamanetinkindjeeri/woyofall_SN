<?php

namespace App\Repository\IRepository;

interface Readable
{
    /**
     * Retourne toutes les entités
     */
    public function selectAll(): array;

    /**
     * Retourne une entité par son identifiant
     */
    public function selectById(string|int $id): ?object;

    /**
     * Retourne les entités correspondant à un ensemble de filtres
     */
    public function selectBy(array $filters): array;
}
