<?php

namespace App\Entity;

class Achat
{
  public function __construct(
    private string $reference,
    private string $codeRecharge,
    private float $nbreKwt,
    private \DateTimeImmutable $date,
    private Tranche $tranche,
    private float $montant,
    private Compteur $compteur,
    private Client $client
  ) {}

  public function getReference(): string
  {
    return $this->reference;
  }

  public function getCodeRecharge(): string
  {
    return $this->codeRecharge;
  }

  public function getNbreKwt(): float
  {
    return $this->nbreKwt;
  }

  public function getDate(): \DateTimeImmutable
  {
    return $this->date;
  }

  public function getTranche(): Tranche
  {
    return $this->tranche;
  }

  public function getMontant(): float
  {
    return $this->montant;
  }

  public function getCompteur(): Compteur
  {
    return $this->compteur;
  }

  public function getClient(): Client
  {
    return $this->client;
  }

  public function toArray(): array
  {
    return [
      'reference' => $this->reference,
      'codeRecharge' => $this->codeRecharge,
      'nbreKwt' => $this->nbreKwt,
      'date' => $this->date->format('Y-m-d H:i:s'),
      'tranche' => $this->tranche->toArray(),
      'montant' => $this->montant,
      'compteur' => $this->compteur->toArray(),
      'client' => $this->client->toArray()
    ];
  }

  public static function toObject(array $data): self
  {
    return new self(
      reference: $data['reference'],
      codeRecharge: $data['codeRecharge'],
      nbreKwt: $data['nbreKwt'],
      date: new \DateTimeImmutable($data['date']),
      tranche: Tranche::toObject($data['tranche']),
      montant: $data['montant'],
      compteur: Compteur::toObject($data['compteur']),
      client: Client::toObject($data['client'])
    );
  }
}
