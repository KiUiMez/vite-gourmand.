-- ============================================================
--  VITE & GOURMAND — Base de données relationnelle (MySQL)
--  Projet ECF - TP Développeur Web et Web Mobile
--  Stack : HTML/CSS/JS + PHP + MySQL
-- ============================================================

-- Création et sélection de la base
CREATE DATABASE IF NOT EXISTS vite_gourmand
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE vite_gourmand;

-- ============================================================
-- TABLE : role
-- Rôles possibles : utilisateur, employe, administrateur
-- ============================================================
CREATE TABLE role (
    role_id   INT          NOT NULL AUTO_INCREMENT,
    libelle   VARCHAR(50)  NOT NULL,
    PRIMARY KEY (role_id)
);

-- ============================================================
-- TABLE : utilisateur
-- Contient tous les comptes (utilisateurs, employés, admin)
-- Le rôle est défini via la clé étrangère role_id
-- ============================================================
CREATE TABLE utilisateur (
    utilisateur_id  INT           NOT NULL AUTO_INCREMENT,
    email           VARCHAR(255)  NOT NULL UNIQUE,
    password        VARCHAR(255)  NOT NULL,           -- hashé en bcrypt
    nom             VARCHAR(100)  NOT NULL,
    prenom          VARCHAR(100)  NOT NULL,
    telephone       VARCHAR(20)   NULL,
    adresse         VARCHAR(255)  NULL,
    ville           VARCHAR(100)  NULL,
    code_postal     VARCHAR(10)   NULL,
    actif           TINYINT(1)    NOT NULL DEFAULT 1, -- 0 = compte désactivé
    role_id         INT           NOT NULL,
    created_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (utilisateur_id),
    CONSTRAINT fk_utilisateur_role
        FOREIGN KEY (role_id) REFERENCES role(role_id)
);

-- ============================================================
-- TABLE : theme
-- Thèmes associés aux menus : Noël, Pâques, classique, événement
-- ============================================================
CREATE TABLE theme (
    theme_id  INT          NOT NULL AUTO_INCREMENT,
    libelle   VARCHAR(50)  NOT NULL,
    PRIMARY KEY (theme_id)
);

-- ============================================================
-- TABLE : regime
-- Régimes alimentaires : végétarien, vegan, classique, sans gluten...
-- ============================================================
CREATE TABLE regime (
    regime_id  INT          NOT NULL AUTO_INCREMENT,
    libelle    VARCHAR(50)  NOT NULL,
    PRIMARY KEY (regime_id)
);

-- ============================================================
-- TABLE : allergene
-- Liste des allergènes possibles (gluten, lactose, arachides...)
-- ============================================================
CREATE TABLE allergene (
    allergene_id  INT          NOT NULL AUTO_INCREMENT,
    libelle       VARCHAR(100) NOT NULL,
    PRIMARY KEY (allergene_id)
);

-- ============================================================
-- TABLE : horaire
-- Horaires d'ouverture du lundi au dimanche
-- ============================================================
CREATE TABLE horaire (
    horaire_id       INT          NOT NULL AUTO_INCREMENT,
    jour             VARCHAR(20)  NOT NULL,  -- ex : "Lundi"
    heure_ouverture  VARCHAR(10)  NULL,      -- ex : "09:00" (NULL = fermé)
    heure_fermeture  VARCHAR(10)  NULL,
    PRIMARY KEY (horaire_id)
);

-- ============================================================
-- TABLE : menu
-- Le catalogue principal de menus proposés par Vite & Gourmand
-- ============================================================
CREATE TABLE menu (
    menu_id              INT           NOT NULL AUTO_INCREMENT,
    titre                VARCHAR(150)  NOT NULL,
    description          TEXT          NULL,
    nombre_personne_min  INT           NOT NULL,
    prix_par_personne    DOUBLE        NOT NULL,   -- prix pour le minimum de personnes
    conditions           TEXT          NULL,        -- délai de commande, précautions...
    quantite_restante    INT           NOT NULL DEFAULT 0,  -- stock disponible
    theme_id             INT           NOT NULL,
    regime_id            INT           NOT NULL,
    actif                TINYINT(1)    NOT NULL DEFAULT 1,
    created_at           DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (menu_id),
    CONSTRAINT fk_menu_theme
        FOREIGN KEY (theme_id) REFERENCES theme(theme_id),
    CONSTRAINT fk_menu_regime
        FOREIGN KEY (regime_id) REFERENCES regime(regime_id)
);

-- ============================================================
-- TABLE : plat
-- Un plat peut être une entrée, un plat principal ou un dessert
-- Il peut apparaître dans plusieurs menus (relation N-N)
-- ============================================================
CREATE TABLE plat (
    plat_id     INT           NOT NULL AUTO_INCREMENT,
    nom         VARCHAR(150)  NOT NULL,
    type_plat   ENUM('entree', 'plat', 'dessert') NOT NULL,
    photo       BLOB          NULL,                -- image du plat
    PRIMARY KEY (plat_id)
);

-- ============================================================
-- TABLE : menu_plat (relation N-N entre menu et plat)
-- Un menu peut avoir plusieurs plats, un plat peut être dans plusieurs menus
-- ============================================================
CREATE TABLE menu_plat (
    menu_id  INT  NOT NULL,
    plat_id  INT  NOT NULL,
    PRIMARY KEY (menu_id, plat_id),
    CONSTRAINT fk_menuplat_menu
        FOREIGN KEY (menu_id) REFERENCES menu(menu_id) ON DELETE CASCADE,
    CONSTRAINT fk_menuplat_plat
        FOREIGN KEY (plat_id) REFERENCES plat(plat_id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE : plat_allergene (relation N-N entre plat et allergène)
-- ============================================================
CREATE TABLE plat_allergene (
    plat_id      INT  NOT NULL,
    allergene_id INT  NOT NULL,
    PRIMARY KEY (plat_id, allergene_id),
    CONSTRAINT fk_platalg_plat
        FOREIGN KEY (plat_id) REFERENCES plat(plat_id) ON DELETE CASCADE,
    CONSTRAINT fk_platalg_allergene
        FOREIGN KEY (allergene_id) REFERENCES allergene(allergene_id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE : image_menu
-- Galerie d'images pour chaque menu
-- ============================================================
CREATE TABLE image_menu (
    image_id   INT           NOT NULL AUTO_INCREMENT,
    menu_id    INT           NOT NULL,
    chemin     VARCHAR(255)  NOT NULL,  -- chemin relatif vers le fichier image
    PRIMARY KEY (image_id),
    CONSTRAINT fk_imagemenu_menu
        FOREIGN KEY (menu_id) REFERENCES menu(menu_id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE : commande
-- Enregistre toutes les commandes passées par les utilisateurs
-- ============================================================
CREATE TABLE commande (
    commande_id        INT           NOT NULL AUTO_INCREMENT,
    utilisateur_id     INT           NOT NULL,
    menu_id            INT           NOT NULL,
    date_prestation    DATE          NOT NULL,
    heure_prestation   VARCHAR(10)   NOT NULL,
    adresse_livraison  VARCHAR(255)  NOT NULL,
    ville_livraison    VARCHAR(100)  NOT NULL,
    code_postal_livr   VARCHAR(10)   NOT NULL,
    nombre_personnes   INT           NOT NULL,
    prix_menu          DOUBLE        NOT NULL,
    prix_livraison     DOUBLE        NOT NULL DEFAULT 0.00,
    prix_materiel      DOUBLE        NOT NULL DEFAULT 0.00,
    prix_total         DOUBLE        NOT NULL,
    statut             ENUM(
                           'en_attente',
                           'accepte',
                           'en_preparation',
                           'en_livraison',
                           'livre',
                           'attente_materiel',
                           'terminee',
                           'annulee'
                       ) NOT NULL DEFAULT 'en_attente',
    pret_materiel      TINYINT(1)    NOT NULL DEFAULT 0,
    motif_annulation   TEXT          NULL,
    mode_contact       VARCHAR(50)   NULL,   -- 'gsm' ou 'mail'
    created_at         DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (commande_id),
    CONSTRAINT fk_commande_utilisateur
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
    CONSTRAINT fk_commande_menu
        FOREIGN KEY (menu_id) REFERENCES menu(menu_id)
);

-- ============================================================
-- TABLE : suivi_commande
-- Historique de tous les changements de statut d'une commande
-- ============================================================
CREATE TABLE suivi_commande (
    suivi_id      INT           NOT NULL AUTO_INCREMENT,
    commande_id   INT           NOT NULL,
    statut        VARCHAR(50)   NOT NULL,
    commentaire   TEXT          NULL,
    created_at    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (suivi_id),
    CONSTRAINT fk_suivi_commande
        FOREIGN KEY (commande_id) REFERENCES commande(commande_id) ON DELETE CASCADE
);

-- ============================================================
-- TABLE : avis
-- Avis laissés par les utilisateurs après commande terminée
-- statut : 'en_attente', 'valide', 'refuse'
-- ============================================================
CREATE TABLE avis (
    avis_id       INT           NOT NULL AUTO_INCREMENT,
    utilisateur_id INT          NOT NULL,
    commande_id   INT           NOT NULL UNIQUE,  -- 1 avis max par commande
    note          TINYINT       NOT NULL CHECK (note BETWEEN 1 AND 5),
    commentaire   TEXT          NULL,
    statut        ENUM('en_attente', 'valide', 'refuse') NOT NULL DEFAULT 'en_attente',
    created_at    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (avis_id),
    CONSTRAINT fk_avis_utilisateur
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
    CONSTRAINT fk_avis_commande
        FOREIGN KEY (commande_id) REFERENCES commande(commande_id)
);

-- ============================================================
-- TABLE : token_reinit
-- Tokens pour la réinitialisation de mot de passe
-- ============================================================
CREATE TABLE token_reinit (
    token_id       INT           NOT NULL AUTO_INCREMENT,
    utilisateur_id INT           NOT NULL,
    token          VARCHAR(255)  NOT NULL UNIQUE,
    expire_at      DATETIME      NOT NULL,
    utilise        TINYINT(1)    NOT NULL DEFAULT 0,
    PRIMARY KEY (token_id),
    CONSTRAINT fk_token_utilisateur
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id) ON DELETE CASCADE
);

-- ============================================================
--  DONNÉES DE TEST (FIXTURES)
-- ============================================================

-- Rôles
INSERT INTO role (libelle) VALUES
    ('utilisateur'),
    ('employe'),
    ('administrateur');

-- Thèmes
INSERT INTO theme (libelle) VALUES
    ('Noël'),
    ('Pâques'),
    ('Classique'),
    ('Événement');

-- Régimes
INSERT INTO regime (libelle) VALUES
    ('Classique'),
    ('Végétarien'),
    ('Vegan'),
    ('Sans gluten'),
    ('Halal');

-- Allergènes courants (14 allergènes majeurs)
INSERT INTO allergene (libelle) VALUES
    ('Gluten'),
    ('Crustacés'),
    ('Œufs'),
    ('Poissons'),
    ('Arachides'),
    ('Soja'),
    ('Lait / Lactose'),
    ('Fruits à coque'),
    ('Céleri'),
    ('Moutarde'),
    ('Sésame'),
    ('Anhydride sulfureux et sulfites'),
    ('Lupin'),
    ('Mollusques');

-- Horaires (lundi au dimanche)
INSERT INTO horaire (jour, heure_ouverture, heure_fermeture) VALUES
    ('Lundi',    '09:00', '18:00'),
    ('Mardi',    '09:00', '18:00'),
    ('Mercredi', '09:00', '18:00'),
    ('Jeudi',    '09:00', '18:00'),
    ('Vendredi', '09:00', '19:00'),
    ('Samedi',   '10:00', '17:00'),
    ('Dimanche', NULL,    NULL);    -- Fermé le dimanche

-- Compte Administrateur (José) — mot de passe : Admin@1234
-- ⚠️ En production, ce hash doit être généré via password_hash() en PHP
INSERT INTO utilisateur (email, password, nom, prenom, telephone, adresse, ville, code_postal, actif, role_id) VALUES
    (
        'jose@vitegourmand.fr',
        '$2y$12$exampleHashedPasswordForJoseAdmin123456789012345678901',
        'Dupont',
        'José',
        '0600000001',
        '10 Rue du Palais',
        'Bordeaux',
        '33000',
        1,
        3   -- administrateur
    );

-- Compte Employé (Julie)
INSERT INTO utilisateur (email, password, nom, prenom, telephone, adresse, ville, code_postal, actif, role_id) VALUES
    (
        'julie@vitegourmand.fr',
        '$2y$12$exampleHashedPasswordForJulieEmployee123456789012345678',
        'Martin',
        'Julie',
        '0600000002',
        '10 Rue du Palais',
        'Bordeaux',
        '33000',
        1,
        2   -- employe
    );

-- Compte Utilisateur test
INSERT INTO utilisateur (email, password, nom, prenom, telephone, adresse, ville, code_postal, actif, role_id) VALUES
    (
        'client@test.fr',
        '$2y$12$exampleHashedPasswordForTestClient12345678901234567890',
        'Leblanc',
        'Marie',
        '0600000003',
        '5 Avenue de la Gare',
        'Bordeaux',
        '33000',
        1,
        1   -- utilisateur
    );

-- Plats exemples
INSERT INTO plat (nom, type_plat) VALUES
    ('Foie gras maison',                  'entree'),
    ('Salade de chèvre chaud',            'entree'),
    ('Velouté de butternut',              'entree'),
    ('Magret de canard aux cèpes',        'plat'),
    ('Dos de cabillaud beurre blanc',     'plat'),
    ('Risotto aux truffes',               'plat'),
    ('Bûche au chocolat',                 'dessert'),
    ('Tarte Tatin',                       'dessert'),
    ('Panna cotta fruits rouges',         'dessert');

-- Menus exemples
INSERT INTO menu (titre, description, nombre_personne_min, prix_par_personne, conditions, quantite_restante, theme_id, regime_id) VALUES
    (
        'Menu Festif de Noël',
        'Un menu généreux pour célébrer les fêtes de fin d\'année autour d\'une table chaleureuse. Foie gras, magret et bûche au programme.',
        8,
        45.00,
        'Commande obligatoire 7 jours avant la prestation. Conservation au frais entre 0°C et 4°C. Matériel de présentation fourni.',
        10,
        1,  -- Noël
        1   -- Classique
    ),
    (
        'Menu Pâques en Famille',
        'Un repas printanier et coloré pour réunir toute la famille autour d\'une table festive.',
        6,
        35.00,
        'Commande obligatoire 5 jours avant la prestation.',
        8,
        2,  -- Pâques
        1   -- Classique
    ),
    (
        'Menu Végétarien du Marché',
        'Un menu 100% végétarien, frais et de saison, élaboré avec des produits locaux bordelais.',
        4,
        28.00,
        'Commande possible jusqu\'à 3 jours avant la prestation.',
        15,
        3,  -- Classique
        2   -- Végétarien
    );

-- Association menus / plats
INSERT INTO menu_plat (menu_id, plat_id) VALUES
    (1, 1),  -- Menu Noël : Foie gras
    (1, 4),  -- Menu Noël : Magret de canard
    (1, 7),  -- Menu Noël : Bûche au chocolat
    (2, 2),  -- Menu Pâques : Salade chèvre
    (2, 5),  -- Menu Pâques : Cabillaud
    (2, 9),  -- Menu Pâques : Panna cotta
    (3, 3),  -- Menu Végétarien : Velouté butternut
    (3, 6),  -- Menu Végétarien : Risotto truffes
    (3, 8);  -- Menu Végétarien : Tarte Tatin

-- Association plats / allergènes
INSERT INTO plat_allergene (plat_id, allergene_id) VALUES
    (1, 3),  -- Foie gras : Œufs
    (2, 3),  -- Salade chèvre : Œufs
    (2, 7),  -- Salade chèvre : Lait
    (4, 1),  -- Magret : Gluten (sauce)
    (5, 4),  -- Cabillaud : Poissons
    (5, 7),  -- Cabillaud : Lait (beurre)
    (6, 7),  -- Risotto : Lait
    (7, 1),  -- Bûche : Gluten
    (7, 3),  -- Bûche : Œufs
    (7, 7),  -- Bûche : Lait
    (8, 1),  -- Tarte Tatin : Gluten
    (8, 7),  -- Tarte Tatin : Lait
    (9, 7);  -- Panna cotta : Lait

-- Avis de démonstration (en attente de validation)
INSERT INTO avis (utilisateur_id, commande_id, note, commentaire, statut)
    SELECT 3, commande_id, 5,
           'Excellent repas, tout était parfait ! Livraison à l\'heure et équipe très professionnelle.',
           'en_attente'
    FROM commande LIMIT 1;

-- ============================================================
-- FIN DU SCRIPT SQL
-- ============================================================