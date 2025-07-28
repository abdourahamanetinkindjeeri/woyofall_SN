<?php

/**
 * Script de test pour l'API REST
 * Utilisez ce script pour tester toutes les fonctionnalités de l'API
 */

class ApiTester
{
  private string $baseUrl = 'http://localhost:8000';

  public function testGetClients(): void
  {
    echo "🔍 Test : Récupération de tous les clients\n";
    $response = $this->makeRequest('GET', '/api/client');
    $this->displayResponse($response);
    echo "\n";
  }

  public function testGetCompteurs(): void
  {
    echo "🔍 Test : Récupération de tous les compteurs\n";
    $response = $this->makeRequest('GET', '/api/compteur');
    $this->displayResponse($response);
    echo "\n";
  }

  public function testGetTranches(): void
  {
    echo "🔍 Test : Récupération de toutes les tranches\n";
    $response = $this->makeRequest('GET', '/api/tranche');
    $this->displayResponse($response);
    echo "\n";
  }

  public function testGetClientById(int $id): void
  {
    echo "🔍 Test : Récupération du client ID $id\n";
    $response = $this->makeRequest('GET', "/api/client/$id");
    $this->displayResponse($response);
    echo "\n";
  }

  public function testGetCompteurByNumero(string $numero): void
  {
    echo "🔍 Test : Récupération du compteur $numero\n";
    $response = $this->makeRequest('GET', "/api/compteur/$numero");
    $this->displayResponse($response);
    echo "\n";
  }

  public function testGetCompteursByClient(int $clientId): void
  {
    echo "🔍 Test : Récupération des compteurs du client $clientId\n";
    $response = $this->makeRequest('GET', "/api/compteur/client/$clientId");
    $this->displayResponse($response);
    echo "\n";
  }

  public function testCreateClient(): void
  {
    echo "🔍 Test : Création d'un nouveau client\n";
    $data = [
      'nom' => 'Test',
      'prenom' => 'Utilisateur',
      'telephone' => '221701234567',
      'cni' => 'SN1234567890123',
      'adresse' => 'Rue Test 123, Dakar',
      'civilite' => 'M'
    ];
    $response = $this->makeRequest('POST', '/api/client', $data);
    $this->displayResponse($response);
    echo "\n";
  }

  public function testCreateCompteur(): void
  {
    echo "🔍 Test : Création d'un nouveau compteur\n";
    $data = [
      'client_id' => 1,
      'tranche_consommee' => 0.0,
      'mois_courant' => '2024-01'
    ];
    $response = $this->makeRequest('POST', '/api/compteur', $data);
    $this->displayResponse($response);
    echo "\n";
  }

  public function testUpdateConsommation(string $numero, float $kwh): void
  {
    echo "🔍 Test : Mise à jour de la consommation du compteur $numero (+$kwh kWh)\n";
    $data = ['kwh' => $kwh];
    $response = $this->makeRequest('PUT', "/api/compteur/$numero/consommation", $data);
    $this->displayResponse($response);
    echo "\n";
  }

  public function testAchat(): void
  {
    echo "🔍 Test : Effectuer un achat d'électricité\n";
    $data = [
      'client_id' => 1,
      'compteur_numero' => 'CPT000000001',
      'nbre_kwt' => 100.0
    ];
    $response = $this->makeRequest('POST', '/api/achat', $data);
    $this->displayResponse($response);
    echo "\n";
  }

  public function runAllTests(): void
  {
    echo "🚀 Démarrage des tests de l'API REST\n";
    echo "=====================================\n\n";

    // Tests de récupération
    $this->testGetClients();
    $this->testGetCompteurs();
    $this->testGetTranches();

    // Tests spécifiques
    $this->testGetClientById(1);
    $this->testGetCompteurByNumero('CPT000000001');
    $this->testGetCompteursByClient(1);

    // Tests de création
    $this->testCreateClient();
    $this->testCreateCompteur();

    // Tests de mise à jour
    $this->testUpdateConsommation('CPT000000001', 50.0);

    // Tests d'achat
    $this->testAchat();

    echo "✅ Tous les tests sont terminés !\n";
  }

  private function makeRequest(string $method, string $endpoint, array $data = null): array
  {
    $url = $this->baseUrl . $endpoint;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if ($data && in_array($method, ['POST', 'PUT'])) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data))
      ]);
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
      'status' => $httpCode,
      'data' => json_decode($response, true),
      'raw' => $response
    ];
  }

  private function displayResponse(array $response): void
  {
    echo "📊 Status: {$response['status']}\n";

    if ($response['data']) {
      if (isset($response['data']['statut'])) {
        echo "📋 Statut: {$response['data']['statut']}\n";
        echo "📝 Message: {$response['data']['message']}\n";

        if (isset($response['data']['data'])) {
          if (is_array($response['data']['data'])) {
            echo "📊 Données: " . count($response['data']['data']) . " élément(s)\n";
            if (count($response['data']['data']) <= 3) {
              echo "📋 Contenu: " . json_encode($response['data']['data'], JSON_PRETTY_PRINT) . "\n";
            }
          } else {
            echo "📋 Données: " . json_encode($response['data']['data'], JSON_PRETTY_PRINT) . "\n";
          }
        }
      } else {
        echo "📋 Données: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n";
      }
    } else {
      echo "❌ Erreur: Aucune donnée reçue\n";
    }
  }
}

// Exécution des tests
if (php_sapi_name() === 'cli') {
  $tester = new ApiTester();
  $tester->runAllTests();
} else {
  echo "Ce script doit être exécuté en ligne de commande.\n";
  echo "Usage: php test/test_api.php\n";
}
