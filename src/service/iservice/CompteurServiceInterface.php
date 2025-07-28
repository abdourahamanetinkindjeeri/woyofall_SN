<?php

namespace App\Service\IService;

use App\Entity\Compteur;

interface CompteurServiceInterface
{
  public function create(array $data): Compteur;
  public function update(string $numero, array $data): Compteur;
  public function delete(string $numero): bool;
  public function findByNumero(string $numero): ?Compteur;
  public function findByClientId(int $clientId): array;
  public function getAllCompteurs(): array;
  public function updateConsommation(string $numero, float $kwh): bool;
  public function resetConsommation(string $numero): bool;
  public function generateNumeroCompteur(): string;
}
