<?php

namespace App\Migration;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/env.php';

use PDO;

class Seeder
{
  private PDO $pdo;

  public function __construct(PDO $pdo)
  {
    $this->pdo = $pdo;
  }

  public function seedClientsFromApi(): void
  {
    $url = 'https://appdaf-g15c.onrender.com/api/citoyens';
    $json = file_get_contents($url);
    $data = json_decode($json, true);

    if (!isset($data['data']) || !is_array($data['data'])) {
      echo "Erreur lors de la récupération des citoyens.\n";
      return;
    }

    $stmt = $this->pdo->prepare("
            INSERT INTO client (nom, prenom, telephone, cni, adresse, civilite)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

    foreach ($data['data'] as $citoyen) {
      $nom = $citoyen['nom'] ?? '';
      $prenom = $citoyen['prenom'] ?? '';
      $telephone = '77' . substr($citoyen['nci'], -7); // Génération fictive
      $cni = $citoyen['nci'] ?? '';
      $adresse = $citoyen['lieu_naissance'] ?? 'Dakar';
      $civilite = 'M'; // À adapter si besoin
      $stmt->execute([$nom, $prenom, $telephone, $cni, $adresse, $civilite]);
    }

    echo "✅ Clients insérés avec succès depuis l'API.\n";
  }

  public function seedTranches(): void
  {
    $tranches = [
      ['Tranche 1', 0, 150, 91.00],
      ['Tranche 2', 151, 250, 102.00],
      ['Tranche 3', 251, 400, 116.00],
      ['Tranche 4', 401, null, 132.00],
    ];
    $stmt = $this->pdo->prepare("INSERT INTO tranche (nom, min, max, prix_par_kwh) VALUES (?, ?, ?, ?)");
    foreach ($tranches as $t) {
      $stmt->execute($t);
    }
    echo "✅ Tranches insérées avec succès.\n";
  }

  public function seedCompteurs(): void
  {
    $compteurs = [
      // numero, client_id, tranche_consommee, consommation_annuelle, mois_courant, annee_courante, status_tranche, date_creation
      ['CPT001', 1, 0.0, 0.0, '07', '2025', 'Tranche 1', date('Y-m-d H:i:s')],
      ['CPT002', 2, 0.0, 0.0, '07', '2025', 'Tranche 1', date('Y-m-d H:i:s')],
    ];
    $stmt = $this->pdo->prepare("INSERT INTO compteur (numero, client_id, tranche_consommee, consommation_annuelle, mois_courant, annee_courante, status_tranche, date_creation) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($compteurs as $c) {
      $stmt->execute($c);
    }
    echo "✅ Compteurs insérés avec succès.\n";
  }

  public function seedAchats(): void
  {
    $achats = [
      // reference, code_recharge, nbre_kwt, date_achat, tranche_nom, montant, compteur_numero, client_id
      ['ACHAT001', 'CODE123', 50.0, date('Y-m-d H:i:s'), 'Tranche 1', 5000.00, 'CPT001', 1],
      ['ACHAT002', 'CODE456', 75.0, date('Y-m-d H:i:s'), 'Tranche 2', 7500.00, 'CPT002', 2],
    ];
    $stmt = $this->pdo->prepare("INSERT INTO achat (reference, code_recharge, nbre_kwt, date_achat, tranche_nom, montant, compteur_numero, client_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($achats as $a) {
      $stmt->execute($a);
    }
    echo "✅ Achats insérés avec succès.\n";
  }
}

try {
  $dsn = sprintf(
    "%s:host=%s;port=%s;dbname=%s",
    getenv('DB_DRIVER') ?: 'pgsql',
    getenv('DB_HOST') ?: $_ENV['DB_HOST'],
    getenv('DB_PORT') ?: $_ENV['DB_PORT'],
    getenv('DB_NAME') ?: $_ENV['DB_NAME']
  );
  $user = getenv('DB_USERNAME') ?: $_ENV['DB_USERNAME'];
  $pass = getenv('DB_PASSWORD') ?: $_ENV['DB_PASSWORD'];

  $pdo = new PDO($dsn, $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $seeder = new Seeder($pdo);
  $seeder->seedClientsFromApi();
  $seeder->seedTranches();
  $seeder->seedCompteurs();
  $seeder->seedAchats();

  echo "Base de données remplie avec succès.\n";
} catch (\PDOException $e) {
  echo "Erreur : " . $e->getMessage() . "\n";
}
