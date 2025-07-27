<?php

namespace App\Entity;

use DateTimeImmutable;

class Compteur
{
  private string $numero; // Exemple : CPT001234567
  private ?int $clientId;  // Id du client
  private float $trancheConsommee; // kWh consommés pour le mois courant
  private float $consommationAnnuelle; // kWh consommés pour l'année courante
  private string $moisCourant; // Format YYYY-MM
  private string $anneeCourante; // Format YYYY
  private string $statusTranche; // Tranche actuelle (Tranche 1, Tranche 2, etc.)
  private DateTimeImmutable $dateCreation;

  public function __construct(
    string $numero,
    ?int $clientId = null,
    float $trancheConsommee = 0.0,
    float $consommationAnnuelle = 0.0,
    ?string $moisCourant = null,
    ?string $anneeCourante = null,
    ?string $statusTranche = null,
    ?DateTimeImmutable $dateCreation = null
  ) {
    $this->numero = $numero;
    $this->clientId = $clientId;
    $this->trancheConsommee = $trancheConsommee;
    $this->consommationAnnuelle = $consommationAnnuelle;
    $this->moisCourant = $moisCourant ?? (new DateTimeImmutable())->format('Y-m');
    $this->anneeCourante = $anneeCourante ?? (new DateTimeImmutable())->format('Y');
    $this->statusTranche = $statusTranche ?? 'Tranche 1';
    $this->dateCreation = $dateCreation ?? new DateTimeImmutable();
  }

  public function getNumero(): string
  {
    return $this->numero;
  }

  public function getClientId(): ?int
  {
    return $this->clientId;
  }

  public function getTrancheConsommee(): float
  {
    return $this->trancheConsommee;
  }

  public function getConsommationAnnuelle(): float
  {
    return $this->consommationAnnuelle;
  }

  public function getMoisCourant(): string
  {
    return $this->moisCourant;
  }

  public function getAnneeCourante(): string
  {
    return $this->anneeCourante;
  }

  public function getStatusTranche(): string
  {
    return $this->statusTranche;
  }

  public function getDateCreation(): DateTimeImmutable
  {
    return $this->dateCreation;
  }

  public function resetTranche(): void
  {
    $this->trancheConsommee = 0.0;
    $this->moisCourant = (new DateTimeImmutable())->format('Y-m');
    $this->statusTranche = 'Tranche 1';
  }

  public function resetAnnee(): void
  {
    $this->consommationAnnuelle = 0.0;
    $this->anneeCourante = (new DateTimeImmutable())->format('Y');
  }

  public function ajouterConsommation(float $kwh): void
  {
    if ($kwh < 0) {
      throw new \InvalidArgumentException("La consommation ne peut pas être négative.");
    }
    $this->trancheConsommee += $kwh;
    $this->consommationAnnuelle += $kwh;
    $this->updateStatusTranche();
  }

  public function updateStatusTranche(): void
  {
    $totalMensuel = $this->trancheConsommee;

    if ($totalMensuel <= 150) {
      $this->statusTranche = 'Tranche 1';
    } elseif ($totalMensuel <= 250) {
      $this->statusTranche = 'Tranche 2';
    } elseif ($totalMensuel <= 400) {
      $this->statusTranche = 'Tranche 3';
    } else {
      $this->statusTranche = 'Tranche 4';
    }
  }

  public function toArray(): array
  {
    return [
      'numero' => $this->numero,
      'clientId' => $this->clientId,
      'trancheConsommee' => $this->trancheConsommee,
      'consommationAnnuelle' => $this->consommationAnnuelle,
      'moisCourant' => $this->moisCourant,
      'anneeCourante' => $this->anneeCourante,
      'statusTranche' => $this->statusTranche,
      'dateCreation' => $this->dateCreation->format('Y-m-d H:i:s')
    ];
  }

  public static function toObject(array $data): self
  {
    return new self(
      $data['numero'],
      isset($data['clientId']) ? (int) $data['clientId'] : null,
      (float) ($data['trancheConsommee'] ?? 0.0),
      (float) ($data['consommationAnnuelle'] ?? 0.0),
      $data['moisCourant'] ?? null,
      $data['anneeCourante'] ?? null,
      $data['statusTranche'] ?? null,
      isset($data['dateCreation']) ? new DateTimeImmutable($data['dateCreation']) : null
    );
  }
}
