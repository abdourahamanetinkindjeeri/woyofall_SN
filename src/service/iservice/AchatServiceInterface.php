<?php

namespace App\Service\IService;

use App\Entity\Achat;
use App\Entity\Client;
use App\Entity\Compteur;
use App\Entity\Tranche;

interface AchatServiceInterface
{
    /**
     * Effectuer un achat de crédit
     *
     * @param string $numeroCompteur
     * @param float $montant
     * @param Client $client
     * @param Compteur $compteur
     * @param Tranche[] $tranches
     * @return Achat
     */
    public function effectuerAchat(
        string $numeroCompteur,
        float $montant,
        Client $client,
        Compteur $compteur,
        array $tranches
    ): Achat;
}
