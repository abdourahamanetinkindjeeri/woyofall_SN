<?php

namespace App\Repository;

use App\Entity\Achat;
use App\Entity\Client;
use App\Entity\Compteur;
use App\Entity\Tranche;
use App\Repository\IRepository\AchatRepositoryInterface;
use PDO;

class AchatRepository implements AchatRepositoryInterface
{
  private PDO $pdo;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function insert(Achat $achat): void
  {
    $stmt = $this->pdo->prepare("
            INSERT INTO achat (reference, code_recharge, nbre_kwt, date_achat, tranche_nom, montant, compteur_numero, client_id)
            VALUES (:reference, :code_recharge, :nbre_kwt, :date_achat, :tranche_nom, :montant, :compteur_numero, :client_id)
        ");

    $stmt->execute([
      'reference' => $achat->getReference(),
      'code_recharge' => $achat->getCodeRecharge(),
      'nbre_kwt' => $achat->getNbreKwt(),
      'date_achat' => $achat->getDate()->format('Y-m-d H:i:s'),
      'tranche_nom' => $achat->getTranche()->getNom(),
      'montant' => $achat->getMontant(),
      'compteur_numero' => $achat->getCompteur()->getNumero(),
      'client_id' => $achat->getClient()->getId()
    ]);
  }

  public function selectBy(string $reference): ?Achat
  {
    $stmt = $this->pdo->prepare("
            SELECT a.*, c.nom as client_nom, c.prenom as client_prenom, 
                   c.telephone as client_telephone, c.cni as client_cni,
                   c.adresse as client_adresse, c.civilite as client_civilite,
                   t.min as tranche_min, t.max as tranche_max, t.prix_par_kwh as tranche_prix
            FROM achat a
            JOIN client c ON a.client_id = c.id
            JOIN tranche t ON a.tranche_nom = t.nom
            WHERE a.reference = :ref
        ");
    $stmt->execute(['ref' => $reference]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? $this->hydrate($row) : null;
  }

  public function selectAll(): array
  {
    $stmt = $this->pdo->query("
            SELECT a.*, c.nom as client_nom, c.prenom as client_prenom, 
                   c.telephone as client_telephone, c.cni as client_cni,
                   c.adresse as client_adresse, c.civilite as client_civilite,
                   t.min as tranche_min, t.max as tranche_max, t.prix_par_kwh as tranche_prix
            FROM achat a
            JOIN client c ON a.client_id = c.id
            JOIN tranche t ON a.tranche_nom = t.nom
        ");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array_map([$this, 'hydrate'], $rows);
  }

  public function selectsBy(string $numeroCompteur): array
  {
    $stmt = $this->pdo->prepare("
            SELECT a.*, c.nom as client_nom, c.prenom as client_prenom, 
                   c.telephone as client_telephone, c.cni as client_cni,
                   c.adresse as client_adresse, c.civilite as client_civilite,
                   t.min as tranche_min, t.max as tranche_max, t.prix_par_kwh as tranche_prix
            FROM achat a
            JOIN client c ON a.client_id = c.id
            JOIN tranche t ON a.tranche_nom = t.nom
            WHERE a.compteur_numero = :numero
        ");
    $stmt->execute(['numero' => $numeroCompteur]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array_map([$this, 'hydrate'], $rows);
  }

  private function hydrate(array $row): Achat
  {
    $compteur = new Compteur($row['compteur_numero']);

    $client = new Client(
      nom: $row['client_nom'],
      prenom: $row['client_prenom'],
      telephone: $row['client_telephone'],
      cni: $row['client_cni'],
      adresse: $row['client_adresse'],
      civilite: $row['client_civilite'],
      id: (int)$row['client_id']
    );

    $tranche = new Tranche(
      nom: $row['tranche_nom'],
      min: (int)$row['tranche_min'],
      max: $row['tranche_max'] !== null ? (int)$row['tranche_max'] : null,
      prixParKwh: (float)$row['tranche_prix']
    );

    return new Achat(
      reference: $row['reference'],
      codeRecharge: $row['code_recharge'],
      nbreKwt: (float)$row['nbre_kwt'],
      date: new \DateTimeImmutable($row['date_achat']),
      tranche: $tranche,
      montant: (float)$row['montant'],
      compteur: $compteur,
      client: $client
    );
  }
}
