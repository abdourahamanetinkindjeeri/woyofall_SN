<?php

require_once __DIR__ . '/vendor/autoload.php';

// Configuration de la base de données
$config = require __DIR__ . '/config/database.php';
$pdo = new PDO($config['dsn'], $config['username'], $config['password'], $config['options']);

echo "🔌 Insertion des données d'achat...\n";

// Récupérer les clients existants
$stmt = $pdo->query("SELECT id, nom, prenom FROM client ORDER BY id LIMIT 10");
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les compteurs existants
$stmt = $pdo->query("SELECT numero, client_id FROM compteur ORDER BY numero LIMIT 10");
$compteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les tranches existantes
$stmt = $pdo->query("SELECT nom, min, max, prix_par_kwh FROM tranche ORDER BY min");
$tranches = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($clients) || empty($compteurs) || empty($tranches)) {
  echo "❌ Erreur: Aucun client, compteur ou tranche trouvé. Veuillez d'abord exécuter generate_data.php\n";
  exit(1);
}

// Données d'achat de test
$achats = [
  [
    'client_id' => $clients[0]['id'],
    'compteur_numero' => $compteurs[0]['numero'],
    'montant' => 5000.0,
    'nbre_kwt' => 54.95,
    'reference' => 'ACH-' . uniqid(),
    'code_recharge' => '1234-5678-9012-3456-7890',
    'date_achat' => date('Y-m-d H:i:s'),
    'tranche_nom' => $tranches[0]['nom']
  ],
  [
    'client_id' => $clients[1]['id'],
    'compteur_numero' => $compteurs[1]['numero'],
    'montant' => 7500.0,
    'nbre_kwt' => 82.42,
    'reference' => 'ACH-' . uniqid(),
    'code_recharge' => '2345-6789-0123-4567-8901',
    'date_achat' => date('Y-m-d H:i:s'),
    'tranche_nom' => $tranches[0]['nom']
  ],
  [
    'client_id' => $clients[2]['id'],
    'compteur_numero' => $compteurs[2]['numero'],
    'montant' => 12000.0,
    'nbre_kwt' => 131.87,
    'reference' => 'ACH-' . uniqid(),
    'code_recharge' => '3456-7890-1234-5678-9012',
    'date_achat' => date('Y-m-d H:i:s'),
    'tranche_nom' => $tranches[1]['nom']
  ],
  [
    'client_id' => $clients[3]['id'],
    'compteur_numero' => $compteurs[3]['numero'],
    'montant' => 20000.0,
    'nbre_kwt' => 219.78,
    'reference' => 'ACH-' . uniqid(),
    'code_recharge' => '4567-8901-2345-6789-0123',
    'date_achat' => date('Y-m-d H:i:s'),
    'tranche_nom' => $tranches[1]['nom']
  ],
  [
    'client_id' => $clients[4]['id'],
    'compteur_numero' => $compteurs[4]['numero'],
    'montant' => 35000.0,
    'nbre_kwt' => 384.62,
    'reference' => 'ACH-' . uniqid(),
    'code_recharge' => '5678-9012-3456-7890-1234',
    'date_achat' => date('Y-m-d H:i:s'),
    'tranche_nom' => $tranches[2]['nom']
  ]
];

try {
  // Préparer la requête d'insertion
  $stmt = $pdo->prepare("
        INSERT INTO achat (
            reference, 
            code_recharge, 
            nbre_kwt, 
            date_achat, 
            montant, 
            client_id, 
            compteur_numero, 
            tranche_nom
        ) VALUES (
            :reference, 
            :code_recharge, 
            :nbre_kwt, 
            :date_achat, 
            :montant, 
            :client_id, 
            :compteur_numero, 
            :tranche_nom
        )
    ");

  $insertedCount = 0;

  foreach ($achats as $achat) {
    $stmt->execute([
      ':reference' => $achat['reference'],
      ':code_recharge' => $achat['code_recharge'],
      ':nbre_kwt' => $achat['nbre_kwt'],
      ':date_achat' => $achat['date_achat'],
      ':montant' => $achat['montant'],
      ':client_id' => $achat['client_id'],
      ':compteur_numero' => $achat['compteur_numero'],
      ':tranche_nom' => $achat['tranche_nom']
    ]);

    $insertedCount++;
    echo "✅ Achat inséré: {$achat['reference']} - {$achat['montant']} FCFA - {$achat['nbre_kwt']} kWh\n";
  }

  echo "\n🎉 {$insertedCount} achats insérés avec succès!\n";

  // Afficher un résumé
  $stmt = $pdo->query("SELECT COUNT(*) as total FROM achat");
  $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
  echo "📊 Total des achats en base: {$total}\n";
} catch (PDOException $e) {
  echo "❌ Erreur lors de l'insertion: " . $e->getMessage() . "\n";
  exit(1);
}

echo "\n🚀 Vous pouvez maintenant tester l'API:\n";
echo "curl -X GET http://localhost:8001/api/achat\n";
