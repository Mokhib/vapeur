<?php
// Inclusion du paramétrage global et connexion BDD
require_once 'parametrage/param.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Utilisation de la constante SITE_NAME définie dans param.php[cite: 7] -->
    <title><?= SITE_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">
        <a href="index.php"><?= SITE_NAME ?></a>
    </div>
    <nav>
        <a href="index.php">Accueil</a>
        <?php if (isset($_SESSION['id_user'])): ?>
            <!-- Affichage spécifique si l'utilisateur est admin[cite: 7] -->
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="#">[Panel Admin]</a>
            <?php endif; ?>
            <a href="logout.php">Déconnexion (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
        <?php else: ?>
            <a href="login.php">Connexion</a>
            <a href="register.php">Inscription</a>
        <?php endif; ?>
    </nav>
</header>
<div class="container">