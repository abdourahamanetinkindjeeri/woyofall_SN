<?php

return [
  'api' => [
    'achat' => [
      'GET' => ['AchatController', 'getAllAchats'],
      'POST' => ['AchatController', 'acheter']
    ],
    'client' => [
      'GET' => ['ClientController', 'getAllClients'],
      'POST' => ['ClientController', 'createClient']
    ],
    'client/{id}' => [
      'GET' => ['ClientController', 'getClientById']
    ],
    'tranche' => [
      'GET' => ['TrancheController', 'getAllTranches'],
      'POST' => ['TrancheController', 'createTranche']
    ],
    'compteur' => [
      'GET' => ['CompteurController', 'getAll'],
      'POST' => ['CompteurController', 'create']
    ],
    'compteur/{numero}' => [
      'GET' => ['CompteurController', 'getByNumero'],
      'PUT' => ['CompteurController', 'update'],
      'DELETE' => ['CompteurController', 'delete']
    ],
    'compteur/client/{clientId}' => [
      'GET' => ['CompteurController', 'getByClientId']
    ],
    'compteur/{numero}/consommation' => [
      'PUT' => ['CompteurController', 'updateConsommation']
    ],
    'compteur/{numero}/reset' => [
      'POST' => ['CompteurController', 'resetConsommation']
    ]
  ]
];
