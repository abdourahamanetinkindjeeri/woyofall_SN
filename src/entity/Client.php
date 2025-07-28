<?php

namespace App\Entity;

class Client
{
  private int $id;
  private string $nom;
  private string $prenom;
  private string $telephone;
  private string $cni;
  private string $adresse;
  private string $civilite;

  public function __construct(
    int $id = 0,
    string $nom = '',
    string $prenom = '',
    string $telephone = '',
    string $cni = '',
    string $adresse = '',
    string $civilite = ''
  ) {
    $this->id = $id;
    $this->nom = $nom;
    $this->prenom = $prenom;
    $this->telephone = $telephone;
    $this->cni = $cni;
    $this->adresse = $adresse;
    $this->civilite = $civilite;
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function setId(int $id): void
  {
    $this->id = $id;
  }

  public function getNom(): string
  {
    return $this->nom;
  }

  public function getPrenom(): string
  {
    return $this->prenom;
  }

  public function getNomComplet(): string
  {
    return $this->prenom . ' ' . $this->nom;
  }

  public function getTelephone(): string
  {
    return $this->telephone;
  }

  public function getCni(): string
  {
    return $this->cni;
  }

  public function getAdresse(): string
  {
    return $this->adresse;
  }

  public function getCivilite(): string
  {
    return $this->civilite;
  }

  public static function toObject(array $row): self
  {
    return new self(
      id: (int)($row['id'] ?? 0),
      nom: $row['nom'] ?? '',
      prenom: $row['prenom'] ?? '',
      telephone: $row['telephone'] ?? '',
      cni: $row['cni'] ?? '',
      adresse: $row['adresse'] ?? '',
      civilite: $row['civilite'] ?? ''
    );
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'nom' => $this->nom,
      'prenom' => $this->prenom,
      'telephone' => $this->telephone,
      'cni' => $this->cni,
      'adresse' => $this->adresse,
      'civilite' => $this->civilite,
    ];
  }
}
