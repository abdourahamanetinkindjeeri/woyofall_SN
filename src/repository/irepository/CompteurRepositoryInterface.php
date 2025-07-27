<?php

namespace App\Repository\IRepository;

use App\Entity\Compteur;

interface CompteurRepositoryInterface extends Readable
{
  public function create(Compteur $compteur): Compteur;
  public function update(Compteur $compteur): Compteur;
  public function delete(string $numero): bool;
  public function findByNumero(string $numero): ?Compteur;
  public function findByClientId(int $clientId): array;
  public function updateConsommation(string $numero, float $kwh): bool;
  public function resetConsommation(string $numero): bool;
}
