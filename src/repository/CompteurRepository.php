<?php

namespace App\Repository;

use App\Entity\Compteur;
use App\Repository\IRepository\CompteurRepositoryInterface;
use PDO;

class CompteurRepository implements CompteurRepositoryInterface
{
  private PDO $pdo;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function create(Compteur $compteur): Compteur
  {
    $sql = "INSERT INTO compteur (numero, client_id, tranche_consommee, consommation_annuelle, mois_courant, annee_courante, status_tranche, date_creation) 
                VALUES (:numero, :client_id, :tranche_consommee, :consommation_annuelle, :mois_courant, :annee_courante, :status_tranche, :date_creation)";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
      'numero' => $compteur->getNumero(),
      'client_id' => $compteur->getClientId(),
      'tranche_consommee' => $compteur->getTrancheConsommee(),
      'consommation_annuelle' => $compteur->getConsommationAnnuelle(),
      'mois_courant' => $compteur->getMoisCourant(),
      'annee_courante' => $compteur->getAnneeCourante(),
      'status_tranche' => $compteur->getStatusTranche(),
      'date_creation' => $compteur->getDateCreation()->format('Y-m-d H:i:s')
    ]);

    return $compteur;
  }

  public function update(Compteur $compteur): Compteur
  {
    $sql = "UPDATE compteur SET 
                client_id = :client_id,
                tranche_consommee = :tranche_consommee,
                consommation_annuelle = :consommation_annuelle,
                mois_courant = :mois_courant,
                annee_courante = :annee_courante,
                status_tranche = :status_tranche
                WHERE numero = :numero";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
      'numero' => $compteur->getNumero(),
      'client_id' => $compteur->getClientId(),
      'tranche_consommee' => $compteur->getTrancheConsommee(),
      'consommation_annuelle' => $compteur->getConsommationAnnuelle(),
      'mois_courant' => $compteur->getMoisCourant(),
      'annee_courante' => $compteur->getAnneeCourante(),
      'status_tranche' => $compteur->getStatusTranche()
    ]);

    return $compteur;
  }

  public function delete(string $numero): bool
  {
    $sql = "DELETE FROM compteur WHERE numero = :numero";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute(['numero' => $numero]);
  }

  public function findByNumero(string $numero): ?Compteur
  {
    $sql = "SELECT * FROM compteur WHERE numero = :numero";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['numero' => $numero]);

    $data = $stmt->fetch();
    return $data ? Compteur::toObject($data) : null;
  }

  public function findByClientId(int $clientId): array
  {
    $sql = "SELECT * FROM compteur WHERE client_id = :client_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['client_id' => $clientId]);

    $compteurs = [];
    while ($data = $stmt->fetch()) {
      $compteurs[] = Compteur::toObject($data);
    }

    return $compteurs;
  }

  public function updateConsommation(string $numero, float $kwh): bool
  {
    // D'abord, récupérer le compteur pour calculer le nouveau statut
    $compteur = $this->findByNumero($numero);
    if (!$compteur) {
      return false;
    }

    // Ajouter la consommation
    $compteur->ajouterConsommation($kwh);

    // Mettre à jour en base
    $sql = "UPDATE compteur SET 
                tranche_consommee = :tranche_consommee,
                consommation_annuelle = :consommation_annuelle,
                status_tranche = :status_tranche
                WHERE numero = :numero";

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
      'numero' => $numero,
      'tranche_consommee' => $compteur->getTrancheConsommee(),
      'consommation_annuelle' => $compteur->getConsommationAnnuelle(),
      'status_tranche' => $compteur->getStatusTranche()
    ]);
  }

  public function resetConsommation(string $numero): bool
  {
    $sql = "UPDATE compteur SET 
                tranche_consommee = 0,
                mois_courant = :mois_courant
                WHERE numero = :numero";

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
      'numero' => $numero,
      'mois_courant' => (new \DateTimeImmutable())->format('Y-m')
    ]);
  }

  public function selectAll(): array
  {
    $sql = "SELECT * FROM compteur ORDER BY date_creation DESC";
    $stmt = $this->pdo->query($sql);

    $compteurs = [];
    while ($data = $stmt->fetch()) {
      $compteurs[] = Compteur::toObject($data);
    }

    return $compteurs;
  }

  public function selectById(string|int $id): ?object
  {
    return $this->findByNumero((string) $id);
  }

  public function selectBy(array $filters): array
  {
    $sql = "SELECT * FROM compteur WHERE 1=1";
    $params = [];

    if (isset($filters['client_id'])) {
      $sql .= " AND client_id = :client_id";
      $params['client_id'] = $filters['client_id'];
    }

    if (isset($filters['mois_courant'])) {
      $sql .= " AND mois_courant = :mois_courant";
      $params['mois_courant'] = $filters['mois_courant'];
    }

    $sql .= " ORDER BY date_creation DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    $compteurs = [];
    while ($data = $stmt->fetch()) {
      $compteurs[] = Compteur::toObject($data);
    }

    return $compteurs;
  }
}
