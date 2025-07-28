<?php

namespace App\Entity;

class Adresse
{
    private int $id;
    private string $rue;
    private string $ville;
    private string $pays;

    public function __construct(int $id = 0, string $rue = '', string $ville = '', string $pays = '')
    {
        $this->id = $id;
        $this->rue = $rue;
        $this->ville = $ville;
        $this->pays = $pays;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getRue(): string
    {
        return $this->rue;
    }

    public function setRue(string $rue): void
    {
        $this->rue = $rue;
    }

    public function getVille(): string
    {
        return $this->ville;
    }

    public function setVille(string $ville): void
    {
        $this->ville = $ville;
    }

    public function getPays(): string
    {
        return $this->pays;
    }

    public function setPays(string $pays): void
    {
        $this->pays = $pays;
    }
}
