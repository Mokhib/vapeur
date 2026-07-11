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
    <script src="script.js" defer></script>
</head>
<body>

<header>
    <div class="logo">
        <a href="index.php"><?= SITE_NAME ?></a>
    </div>
    <nav>
        <a href="index.php">Accueil</a>
        <?php if (isset($_SESSION['id_user'])): ?>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin.php">Administration</a>
            <?php endif; ?>
            <a href="profil.php">Profil</a>
            <a href="logout.php">Déconnexion (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
        <?php else: ?>
            <a href="login.php">Connexion</a>
            <a href="register.php">Inscription</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">
