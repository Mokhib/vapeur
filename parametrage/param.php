<?php
// Paramétrage global du site : session, constantes et connexion à la base de données.

// Démarrage de la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Paramètres de connexion à la base de données (compte local par défaut : root, mot de passe vide)
define('DB_HOST', 'localhost');
define('DB_NAME', 'clickvault');
define('DB_USER', 'root');
define('DB_PASS', '');

// Paramétrage des constantes globales du site
define('SITE_NAME', 'Vapeur');

// Connexion à la base de données via PDO, avec remontée d'erreurs par exception
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
