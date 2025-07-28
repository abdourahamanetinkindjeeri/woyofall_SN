<?php

$schemas = [
  'client' => [
    'id' => ['type' => 'SERIAL', 'primary' => true],
    'nom' => ['type' => 'VARCHAR(100)', 'not_null' => true],
    'prenom' => ['type' => 'VARCHAR(100)', 'not_null' => true],
    'telephone' => ['type' => 'VARCHAR(20)', 'not_null' => true],
    'cni' => ['type' => 'VARCHAR(20)', 'not_null' => true],
    'adresse' => ['type' => 'TEXT', 'not_null' => true],
    'civilite' => ['type' => 'VARCHAR(5)', 'not_null' => true],
  ],
  'compteur' => [
    'numero' => ['type' => 'VARCHAR(50)', 'primary' => true],
    'client_id' => ['type' => 'INTEGER', 'not_null' => true, 'foreign' => ['client', 'id']],
    'tranche_consommee' => ['type' => 'DECIMAL(10,2)', 'default' => 0.0],
    'consommation_annuelle' => ['type' => 'DECIMAL(10,2)', 'default' => 0.0],
    'mois_courant' => ['type' => 'VARCHAR(7)', 'not_null' => true],
    'annee_courante' => ['type' => 'VARCHAR(4)', 'not_null' => true],
    'status_tranche' => ['type' => 'VARCHAR(20)', 'default' => 'Tranche 1'],
    'date_creation' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
  ],
  'achat' => [
    'reference' => ['type' => 'VARCHAR(50)', 'primary' => true],
    'code_recharge' => ['type' => 'VARCHAR(50)', 'not_null' => true],
    'nbre_kwt' => ['type' => 'DECIMAL(10,2)', 'not_null' => true],
    'date_achat' => ['type' => 'TIMESTAMP', 'not_null' => true],
    'tranche_nom' => ['type' => 'VARCHAR(50)', 'not_null' => true, 'foreign' => ['tranche', 'nom']],
    'montant' => ['type' => 'DECIMAL(10,2)', 'not_null' => true],
    'compteur_numero' => ['type' => 'VARCHAR(50)', 'not_null' => true, 'foreign' => ['compteur', 'numero']],
    'client_id' => ['type' => 'INTEGER', 'not_null' => true, 'foreign' => ['client', 'id']],
  ],
];

return $schemas;
