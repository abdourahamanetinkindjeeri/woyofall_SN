# Tests de l'API REST

Ce dossier contient tous les fichiers de test pour l'API REST de l'application AppWoyofall.

## 📁 Fichiers de test

### Fichiers HTTP (.http)

Ces fichiers peuvent être utilisés avec des extensions comme **REST Client** dans VS Code ou **Thunder Client**.

- **`api_clients.http`** : Tests pour les endpoints de clients
- **`api_compteurs.http`** : Tests pour les endpoints de compteurs
- **`api_tranches.http`** : Tests pour les endpoints de tranches
- **`api_achats.http`** : Tests pour les endpoints d'achats
- **`api_complete_test.http`** : Tests complets de tous les endpoints

### Script PHP

- **`test_api.php`** : Script PHP pour tester l'API programmatiquement

## 🚀 Comment utiliser les tests

### 1. Avec VS Code + REST Client

1. Installez l'extension "REST Client" dans VS Code
2. Ouvrez un fichier `.http`
3. Cliquez sur "Send Request" au-dessus de chaque requête

### 2. Avec Thunder Client

1. Installez l'extension "Thunder Client" dans VS Code
2. Ouvrez Thunder Client
3. Copiez-collez les requêtes depuis les fichiers `.http`

### 3. Avec le script PHP

```bash
# Démarrer le serveur
./start_server.sh

# Dans un autre terminal, exécuter les tests
php test/test_api.php
```

### 4. Avec curl

```bash
# Exemple : Récupérer tous les clients
curl -X GET http://localhost:8000/api/client

# Exemple : Créer un client
curl -X POST http://localhost:8000/api/client \
  -H "Content-Type: application/json" \
  -d '{
    "nom": "Test",
    "prenom": "Utilisateur",
    "telephone": "221701234567",
    "cni": "SN1234567890123",
    "adresse": "Rue Test 123, Dakar",
    "civilite": "M"
  }'
```

## 📊 Endpoints disponibles

### Clients

- `GET /api/client` - Récupérer tous les clients
- `POST /api/client` - Créer un nouveau client
- `GET /api/client/{id}` - Récupérer un client par ID
- `PUT /api/client/{id}` - Mettre à jour un client
- `DELETE /api/client/{id}` - Supprimer un client

### Compteurs

- `GET /api/compteur` - Récupérer tous les compteurs
- `POST /api/compteur` - Créer un nouveau compteur
- `GET /api/compteur/{numero}` - Récupérer un compteur par numéro
- `PUT /api/compteur/{numero}` - Mettre à jour un compteur
- `DELETE /api/compteur/{numero}` - Supprimer un compteur
- `GET /api/compteur/client/{client_id}` - Récupérer les compteurs d'un client
- `PUT /api/compteur/{numero}/consommation` - Mettre à jour la consommation
- `POST /api/compteur/{numero}/reset` - Réinitialiser la consommation

### Tranches

- `GET /api/tranche` - Récupérer toutes les tranches
- `POST /api/tranche` - Créer une nouvelle tranche
- `GET /api/tranche/{nom}` - Récupérer une tranche par nom
- `PUT /api/tranche/{nom}` - Mettre à jour une tranche
- `DELETE /api/tranche/{nom}` - Supprimer une tranche

### Achats

- `POST /api/achat` - Effectuer un achat d'électricité
- `GET /api/achat` - Récupérer tous les achats
- `GET /api/achat/{reference}` - Récupérer un achat par référence
- `GET /api/achat/client/{client_id}` - Récupérer les achats d'un client
- `GET /api/achat/compteur/{numero}` - Récupérer les achats d'un compteur
- `DELETE /api/achat/{reference}` - Supprimer un achat

## 🔧 Configuration

1. Assurez-vous que le serveur PHP est démarré :

   ```bash
   ./start_server.sh
   ```

2. Vérifiez que la base de données est configurée :

   ```bash
   php test_connection.php
   ```

3. Si vous utilisez un port différent de 8000, mettez à jour les URLs dans les fichiers de test.

## 📈 Données de test

L'application contient déjà :

- **32 clients** avec des noms sénégalais
- **30 compteurs** avec des consommations réalistes
- **4 tranches tarifaires** selon les tarifs Sénénelec
- **0 achats** (à créer via les tests)

## 🐛 Dépannage

### Erreur "Address already in use"

```bash
# Tuer le processus sur le port 8000
sudo lsof -ti:8000 | xargs kill -9

# Ou utiliser un port différent
php -S localhost:8001
```

### Erreur de connexion à la base de données

```bash
# Vérifier la configuration
php test_connection.php

# Reconfigurer si nécessaire
php setup_database.php
```

### Erreur "No data received"

- Vérifiez que le serveur est démarré
- Vérifiez l'URL dans les requêtes
- Vérifiez que les routes sont bien définies dans `index.php`
