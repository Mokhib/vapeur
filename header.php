<?php
// En-tête commun à toutes les pages : paramétrage, meta, navigation.
require_once 'parametrage/param.php';
require_once 'fonction/fonctions.php';

// $titrePage et $descriptionPage peuvent être définis par la page appelante avant l'inclusion.
if (!isset($titrePage)) {
    $titrePage = SITE_NAME;
}
if (!isset($descriptionPage)) {
    $descriptionPage = "Vapeur, la plateforme communautaire des jeux d'aventure point-and-click : découvrez, notez et commentez vos classiques préférés.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($descriptionPage) ?>">
    <title><?= htmlspecialchars($titrePage) ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 40 40'%3E%3Ccircle cx='20' cy='20' r='18' fill='%2314171a'/%3E%3Ccircle cx='20' cy='20' r='15' stroke='%235fd4c4' stroke-width='2.5' fill='none'/%3E%3Cg stroke='%238b969c' stroke-width='2' stroke-linecap='round'%3E%3Cline x1='20' y1='7' x2='20' y2='10'/%3E%3Cline x1='9' y1='20' x2='12' y2='20'/%3E%3Cline x1='31' y1='20' x2='28' y2='20'/%3E%3C/g%3E%3Cline x1='20' y1='20' x2='27' y2='12' stroke='%23e8734a' stroke-width='2.5' stroke-linecap='round'/%3E%3Ccircle cx='20' cy='20' r='3' fill='%235fd4c4'/%3E%3C/svg%3E">
    <script src="script.js" defer></script>
</head>
<body>

<div class="deco-rail deco-rail--left" aria-hidden="true">
    <svg class="deco-gauge deco-gauge--1" width="38" height="38" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
        <circle cx="20" cy="20" r="16" stroke="var(--border-color)" stroke-width="1.5" fill="none"/>
        <line x1="20" y1="20" x2="26" y2="13" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round"/>
        <circle cx="20" cy="20" r="2" fill="var(--border-color)"/>
    </svg>
    <svg class="deco-gauge deco-gauge--2" width="38" height="38" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
        <circle cx="20" cy="20" r="16" stroke="var(--border-color)" stroke-width="1.5" fill="none"/>
        <line x1="20" y1="20" x2="13" y2="27" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round"/>
        <circle cx="20" cy="20" r="2" fill="var(--border-color)"/>
    </svg>
</div>
<div class="deco-rail deco-rail--right" aria-hidden="true">
    <svg class="deco-gauge deco-gauge--3" width="38" height="38" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
        <circle cx="20" cy="20" r="16" stroke="var(--border-color)" stroke-width="1.5" fill="none"/>
        <line x1="20" y1="20" x2="27" y2="27" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round"/>
        <circle cx="20" cy="20" r="2" fill="var(--border-color)"/>
    </svg>
    <svg class="deco-gauge deco-gauge--4" width="38" height="38" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
        <circle cx="20" cy="20" r="16" stroke="var(--border-color)" stroke-width="1.5" fill="none"/>
        <line x1="20" y1="20" x2="14" y2="12" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round"/>
        <circle cx="20" cy="20" r="2" fill="var(--border-color)"/>
    </svg>
</div>

<header>
    <div class="logo">
        <a href="index.php">
            <svg class="logo-mark" width="30" height="30" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <circle cx="20" cy="20" r="17" stroke="var(--accent)" stroke-width="2"/>
                <circle cx="20" cy="20" r="12.5" stroke="var(--border-color)" stroke-width="1"/>
                <g stroke="var(--text-muted)" stroke-width="1.4" stroke-linecap="round">
                    <line x1="20" y1="4.5" x2="20" y2="7.5"/>
                    <line x1="10.2" y1="9.2" x2="12.3" y2="11.3"/>
                    <line x1="5.5" y1="20" x2="8.5" y2="20"/>
                    <line x1="10.2" y1="30.8" x2="12.3" y2="28.7"/>
                    <line x1="29.8" y1="9.2" x2="27.7" y2="11.3"/>
                </g>
                <line x1="20" y1="20" x2="28" y2="11" stroke="var(--danger)" stroke-width="2" stroke-linecap="round"/>
                <circle cx="20" cy="20" r="2.6" fill="var(--accent)"/>
                <circle cx="20" cy="35.5" r="1.3" fill="var(--border-color)"/>
            </svg>
            <span class="logo-text"><?= SITE_NAME ?></span>
        </a>
    </div>
    <nav>
        <a href="index.php">Accueil</a>
        <?php if (isset($_SESSION['id_user'])) { ?>
            <?php if ($_SESSION['role'] === 'admin') { ?>
                <a href="admin.php">Administration</a>
            <?php } ?>
            <a href="profil.php">Profil</a>
            <a href="logout.php">Déconnexion (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
        <?php } else { ?>
            <a href="login.php">Connexion</a>
            <a href="register.php">Inscription</a>
        <?php } ?>
    </nav>
</header>
<main class="container">
