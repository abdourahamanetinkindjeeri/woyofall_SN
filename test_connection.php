<?php

require_once __DIR__ . '/vendor/autoload.php';

// Configuration de la base de données
$config = require __DIR__ . '/config/environment.php';

try {
  // Connexion à PostgreSQL
  $pdo = new PDO($config['database']['dsn'], $config['database']['username'], $config['database']['password'], $config['database']['options']);

  echo "✅ Connexion à la base de données réussie\n\n";

  // Vérification des tables existantes
  $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
  $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

  echo "📋 Tables existantes :\n";
  foreach ($tables as $table) {
    echo "  - $table\n";
  }
  echo "\n";

  // Vérification du contenu des tables
  $expectedTables = ['client', 'tranche', 'achat'];
  foreach ($expectedTables as $table) {
    if (in_array($table, $tables)) {
      $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
      $count = $stmt->fetchColumn();
      echo "📊 Table '$table' : $count enregistrement(s)\n";

      // Afficher quelques exemples pour la table tranche
      if ($table === 'tranche' && $count > 0) {
        $stmt = $pdo->query("SELECT * FROM tranche LIMIT 3");
        $tranches = $stmt->fetchAll();
        echo "   Exemples de tranches :\n";
        foreach ($tranches as $tranche) {
          echo "   - {$tranche['nom']}: {$tranche['min']}-{$tranche['max']} kWh à {$tranche['prix_par_kwh']} FCFA\n";
        }
      }
    } else {
      echo "❌ Table '$table' manquante\n";
    }
  }

  echo "\n🎉 Test de connexion réussi !\n";
} catch (PDOException $e) {
  echo "❌ Erreur de connexion à la base de données : " . $e->getMessage() . "\n";
  exit(1);
} catch (Exception $e) {
  echo "❌ Erreur lors du test : " . $e->getMessage() . "\n";
  exit(1);
}
