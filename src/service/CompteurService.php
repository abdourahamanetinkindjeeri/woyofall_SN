<?php

namespace App\Service;

use App\Entity\Compteur;
use App\Repository\IRepository\CompteurRepositoryInterface;
use App\Service\IService\CompteurServiceInterface;

class CompteurService implements CompteurServiceInterface
{
  private CompteurRepositoryInterface $compteurRepository;

  public function __construct(CompteurRepositoryInterface $compteurRepository)
  {
    $this->compteurRepository = $compteurRepository;
  }

  public function create(array $data): Compteur
  {
    // Validation des données
    $this->validateCompteurData($data);

    // Génération du numéro de compteur si non fourni
    if (!isset($data['numero'])) {
      $data['numero'] = $this->generateNumeroCompteur();
    }

    // Vérification que le numéro n'existe pas déjà
    if ($this->findByNumero($data['numero'])) {
      throw new \InvalidArgumentException("Un compteur avec le numéro {$data['numero']} existe déjà.");
    }

    // Vérification que le client existe
    if (isset($data['client_id'])) {
      // Ici on pourrait ajouter une vérification que le client existe
      // en injectant le ClientRepository si nécessaire
    }

    $compteur = new Compteur(
      $data['numero'],
      $data['client_id'] ?? null,
      $data['tranche_consommee'] ?? 0.0,
      $data['mois_courant'] ?? null
    );

    return $this->compteurRepository->create($compteur);
  }

  public function update(string $numero, array $data): Compteur
  {
    $compteur = $this->findByNumero($numero);
    if (!$compteur) {
      throw new \InvalidArgumentException("Compteur avec le numéro $numero non trouvé.");
    }

    // Validation des données
    $this->validateCompteurData($data, true);

    // Mise à jour des propriétés
    if (isset($data['client_id'])) {
      $compteur = new Compteur(
        $numero,
        $data['client_id'],
        $data['tranche_consommee'] ?? $compteur->getTrancheConsommee(),
        $data['mois_courant'] ?? $compteur->getMoisCourant(),
        $compteur->getDateCreation()
      );
    } else {
      $compteur = new Compteur(
        $numero,
        $compteur->getClientId(),
        $data['tranche_consommee'] ?? $compteur->getTrancheConsommee(),
        $data['mois_courant'] ?? $compteur->getMoisCourant(),
        $compteur->getDateCreation()
      );
    }

    return $this->compteurRepository->update($compteur);
  }

  public function delete(string $numero): bool
  {
    $compteur = $this->findByNumero($numero);
    if (!$compteur) {
      throw new \InvalidArgumentException("Compteur avec le numéro $numero non trouvé.");
    }

    return $this->compteurRepository->delete($numero);
  }

  public function findByNumero(string $numero): ?Compteur
  {
    return $this->compteurRepository->findByNumero($numero);
  }

  public function findByClientId(int $clientId): array
  {
    return $this->compteurRepository->findByClientId($clientId);
  }

  public function getAllCompteurs(): array
  {
    return $this->compteurRepository->selectAll();
  }

  public function updateConsommation(string $numero, float $kwh): bool
  {
    $compteur = $this->findByNumero($numero);
    if (!$compteur) {
      throw new \InvalidArgumentException("Compteur avec le numéro $numero non trouvé.");
    }

    if ($kwh < 0) {
      throw new \InvalidArgumentException("La consommation ne peut pas être négative.");
    }

    return $this->compteurRepository->updateConsommation($numero, $kwh);
  }

  public function resetConsommation(string $numero): bool
  {
    $compteur = $this->findByNumero($numero);
    if (!$compteur) {
      throw new \InvalidArgumentException("Compteur avec le numéro $numero non trouvé.");
    }

    return $this->compteurRepository->resetConsommation($numero);
  }

  public function generateNumeroCompteur(): string
  {
    // Format: CPT + 9 chiffres (ex: CPT001234567)
    $prefix = 'CPT';
    $random = str_pad((string) rand(1, 999999999), 9, '0', STR_PAD_LEFT);
    return $prefix . $random;
  }

  private function validateCompteurData(array $data, bool $isUpdate = false): void
  {
    if (!$isUpdate) {
      // Pour la création, le client_id est obligatoire
      if (!isset($data['client_id'])) {
        throw new \InvalidArgumentException("L'ID du client est obligatoire.");
      }
    }

    // Validation du client_id si fourni
    if (isset($data['client_id']) && (!is_numeric($data['client_id']) || $data['client_id'] <= 0)) {
      throw new \InvalidArgumentException("L'ID du client doit être un entier positif.");
    }

    // Validation de la consommation si fournie
    if (isset($data['tranche_consommee'])) {
      if (!is_numeric($data['tranche_consommee']) || $data['tranche_consommee'] < 0) {
        throw new \InvalidArgumentException("La consommation doit être un nombre positif.");
      }
    }

    // Validation du mois courant si fourni
    if (isset($data['mois_courant'])) {
      if (!preg_match('/^\d{4}-\d{2}$/', $data['mois_courant'])) {
        throw new \InvalidArgumentException("Le mois courant doit être au format YYYY-MM.");
      }
    }
  }
}
