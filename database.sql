-- Création de la table client
CREATE TABLE IF NOT EXISTS client (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    cni VARCHAR(20) NOT NULL,
    adresse TEXT NOT NULL,
    civilite VARCHAR(5) NOT NULL
);

-- Création de la table tranche
CREATE TABLE IF NOT EXISTS tranche (
    nom VARCHAR(50) PRIMARY KEY,
    min INTEGER NOT NULL,
    max INTEGER NULL,
    prix_par_kwh DECIMAL(10,2) NOT NULL
);

-- Création de la table compteur
CREATE TABLE IF NOT EXISTS compteur (
    numero VARCHAR(50) PRIMARY KEY,
    client_id INTEGER NOT NULL,
    tranche_consommee DECIMAL(10,2) DEFAULT 0.0,
    consommation_annuelle DECIMAL(10,2) DEFAULT 0.0,
    mois_courant VARCHAR(7) NOT NULL,
    annee_courante VARCHAR(4) NOT NULL,
    status_tranche VARCHAR(20) DEFAULT 'Tranche 1',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client(id)
);

-- Création de la table achat
CREATE TABLE IF NOT EXISTS achat (
    reference VARCHAR(50) PRIMARY KEY,
    code_recharge VARCHAR(50) NOT NULL,
    nbre_kwt DECIMAL(10,2) NOT NULL,
    date_achat TIMESTAMP NOT NULL,
    tranche_nom VARCHAR(50) NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    compteur_numero VARCHAR(50) NOT NULL,
    client_id INTEGER NOT NULL,
    FOREIGN KEY (tranche_nom) REFERENCES tranche(nom),
    FOREIGN KEY (client_id) REFERENCES client(id),
    FOREIGN KEY (compteur_numero) REFERENCES compteur(numero)
);

-- Insertion des données de base pour les tranches
INSERT INTO tranche (nom, min, max, prix_par_kwh) VALUES
('Tranche 1', 0, 150, 91.00),
('Tranche 2', 151, 250, 102.00),
('Tranche 3', 251, 400, 116.00),
('Tranche 4', 401, NULL, 132.00)
ON CONFLICT (nom) DO NOTHING; 