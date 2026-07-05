<?php
// Création du fichier param.php centralisant constantes et paramètres[cite: 7]

// Démarrage de la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Paramètres de connexion à la base de données (login root, mdp vide)[cite: 7]
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'clickvault');

// Paramétrage des constantes globales du site
define('SITE_NAME', 'ClickVault');
define('BASE_URL', 'http://localhost/clickvault/');

// Etablissement de la connexion procédurale à la base de données
$connexion = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Vérification de la connexion
if (!$connexion) {
    // Interruption du script en cas d'erreur critique
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

// Configuration du jeu de caractères en UTF-8 pour éviter les problèmes d'affichage
mysqli_set_charset($connexion, "utf8mb4");
?>