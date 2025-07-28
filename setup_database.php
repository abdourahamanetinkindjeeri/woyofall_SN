<?php

require_once __DIR__ . '/vendor/autoload.php';

// Configuration de la base de données
$config = require __DIR__ . '/config/environment.php';

try {
  // Connexion à PostgreSQL
  $pdo = new PDO($config['database']['dsn'], $config['database']['username'], $config['database']['password'], $config['database']['options']);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  echo "✅ Connexion à la base de données réussie\n";

  // Lecture et exécution du fichier SQL
  $sql = file_get_contents(__DIR__ . '/database.sql');

  // Exécution des requêtes SQL
  $pdo->exec($sql);

  echo "✅ Structure de la base de données créée avec succès\n";
  echo "✅ Données de base insérées\n";

  // Vérification des tables créées
  $tables = ['client', 'tranche', 'achat'];
  foreach ($tables as $table) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
    $count = $stmt->fetchColumn();
    echo "📊 Table '$table' : $count enregistrement(s)\n";
  }

  echo "\n🎉 Configuration de la base de données terminée avec succès !\n";
} catch (PDOException $e) {
  echo "❌ Erreur de connexion à la base de données : " . $e->getMessage() . "\n";
  exit(1);
} catch (Exception $e) {
  echo "❌ Erreur lors de la configuration : " . $e->getMessage() . "\n";
  exit(1);
}
