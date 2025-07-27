<?php

namespace App\Entity;

class Tranche
{
    private string $nom;
    private int $min;
    private ?int $max;
    private float $prixParKwh;

    public function __construct(string $nom, int $min, ?int $max, float $prixParKwh)
    {
        $this->nom = $nom;
        $this->min = $min;
        $this->max = $max;
        $this->prixParKwh = $prixParKwh;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getMin(): int
    {
        return $this->min;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function getPrixParKwh(): float
    {
        return $this->prixParKwh;
    }

    public function contient(int $kw): bool
    {
        return $kw >= $this->min && ($this->max === null || $kw <= $this->max);
    }

    public function toArray(): array
    {
        return [
            'nom' => $this->nom,
            'min' => $this->min,
            'max' => $this->max,
            'prixParKwh' => $this->prixParKwh
        ];
    }

    public static function toObject(array $data): self
    {
        return new self(
            $data['nom'],
            (int) $data['min'],
            isset($data['max']) ? (int) $data['max'] : null,
            (float) $data['prixParKwh']
        );
    }
}
