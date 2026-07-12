<?php
// Paramétrage global du site : session, constantes et connexion à la base de données.

// Démarrage de la session
session_start();

// Paramètres de connexion à la base de données (compte local par défaut : root, mot de passe vide)
define('DB_HOST', 'mysql-vapeur.alwaysdata.net');
define('DB_NAME', 'vapeur_clickvault');
define('DB_USER', 'vapeur');
define('DB_PASS', 'aF}3;.YCYv]mUd"');

// Paramétrage des constantes globales du site
define('SITE_NAME', 'Vapeur');
define('DOSSIER_SITE', '/home/vapeur/www');

// Connexion à la base de données via PDO, avec remontée d'erreurs par exception
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
