<?php

namespace App\Repository;

use App\Entity\Client;
use App\Repository\IRepository\ClientRepositoryInterface;
use App\Repository\IRepository\Readable;
use PDO;

class ClientRepository implements ClientRepositoryInterface, Readable
{
  private string $table = 'client';
  private PDO $pdo;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function insert(Client $entity): Client
  {
    $requete = "INSERT INTO {$this->table}
            (nom, prenom, telephone, cni, civilite, adresse)
            VALUES (:nom, :prenom, :telephone, :cni, :civilite, :adresse)";

    $statement = $this->pdo->prepare($requete);

    $statement->execute([
      ':nom'       => $entity->getNom(),
      ':prenom'    => $entity->getPrenom(),
      ':telephone' => $entity->getTelephone(),
      ':cni'       => $entity->getCni(),
      ':civilite'  => $entity->getCivilite(),
      ':adresse'   => $entity->getAdresse()
    ]);

    // Récupérer l'ID généré
    $entity->setId((int)$this->pdo->lastInsertId());

    return $entity;
  }

  public function update(Client $entity, array $filter): Client
  {
    $data = [
      'nom'       => $entity->getNom(),
      'prenom'    => $entity->getPrenom(),
      'telephone' => $entity->getTelephone(),
      'cni'       => $entity->getCni(),
      'civilite'  => $entity->getCivilite(),
      'adresse'   => $entity->getAdresse()
    ];

    $setClauses = [];
    foreach ($data as $col => $val) {
      $setClauses[] = "$col = :$col";
    }

    $whereClauses = [];
    foreach ($filter as $col => $val) {
      $whereClauses[] = "$col = :filter_$col";
    }

    $sql = "UPDATE {$this->table} SET " . implode(', ', $setClauses)
      . " WHERE " . implode(' AND ', $whereClauses);

    $stmt = $this->pdo->prepare($sql);

    $params = [];
    foreach ($data as $col => $val) {
      $params[":$col"] = $val;
    }
    foreach ($filter as $col => $val) {
      $params[":filter_$col"] = $val;
    }

    $stmt->execute($params);

    return $entity;
  }

  public function selectAll(): array
  {
    $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array_map(fn($row) => Client::toObject($row), $results);
  }

  public function selectById(string|int $id): ?Client
  {
    $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    return $data ? Client::toObject($data) : null;
  }

  // Implémentation pour l'interface Readable
  public function selectBy(array $filters): array
  {
    $where = [];
    $params = [];

    foreach ($filters as $key => $value) {
      $where[] = "$key = :$key";
      $params[":$key"] = $value;
    }

    $sql = "SELECT * FROM {$this->table}";
    if ($where) {
      $sql .= " WHERE " . implode(' AND ', $where);
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array_map(fn($row) => Client::toObject($row), $rows);
  }

  // Implémentation pour l'interface ClientRepositoryInterface
  public function findOneBy(array $criteria): ?Client
  {
    $where = [];
    $params = [];

    foreach ($criteria as $key => $value) {
      $where[] = "$key = :$key";
      $params[":$key"] = $value;
    }

    $sql = "SELECT * FROM {$this->table}";
    if ($where) {
      $sql .= " WHERE " . implode(' AND ', $where);
    }
    $sql .= " LIMIT 1";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? Client::toObject($row) : null;
  }
}
