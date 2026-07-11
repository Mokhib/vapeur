<?php
require_once 'parametrage/param.php';
require_once 'fonction/fonctions.php';

$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : '';
$tri = isset($_GET['tri']) ? $_GET['tri'] : 'recent';
$jeux = obtenirJeux($pdo, $recherche, $tri);

$titrePage = SITE_NAME . ' - Accueil';
$descriptionPage = "Parcourez le catalogue de jeux d'aventure point-and-click de Vapeur et découvrez les avis de la communauté.";
include 'header.php';
?>

<h1>Bienvenue sur <?= SITE_NAME ?></h1>
<p>Découvrez notre sélection de jeux d'aventure point-and-click.</p>

<form method="GET" action="index.php" class="search-bar">
    <label for="recherche" class="visually-hidden">Rechercher un jeu</label>
    <input type="text" id="recherche" name="recherche" placeholder="Rechercher un jeu ou un studio..." value="<?= htmlspecialchars($recherche) ?>">
    <label for="tri" class="visually-hidden">Trier par</label>
    <select id="tri" name="tri">
        <option value="recent" <?= $tri === 'recent' ? 'selected' : '' ?>>Plus récents</option>
        <option value="ancien" <?= $tri === 'ancien' ? 'selected' : '' ?>>Plus anciens</option>
        <option value="titre" <?= $tri === 'titre' ? 'selected' : '' ?>>Titre (A-Z)</option>
    </select>
    <button type="submit" class="btn">Filtrer</button>
</form>

<div class="games-grid">
    <?php if (count($jeux) > 0): ?>
        <?php foreach ($jeux as $jeu): ?>
            <div class="game-card">
                <?php if (!empty($jeu['cover_image']) && file_exists('images/' . $jeu['cover_image'])): ?>
                    <img src="images/<?= htmlspecialchars($jeu['cover_image']) ?>" alt="Jaquette du jeu <?= htmlspecialchars($jeu['title']) ?>" class="game-cover">
                <?php else: ?>
                    <div class="game-cover game-cover--placeholder" aria-hidden="true"><?= htmlspecialchars($jeu['title']) ?></div>
                <?php endif; ?>
                <h3><?= htmlspecialchars($jeu['title']) ?> (<?= htmlspecialchars($jeu['release_year']) ?>)</h3>
                <p><strong>Studio :</strong> <?= htmlspecialchars($jeu['developer']) ?></p>
                <p><?= htmlspecialchars(mb_substr($jeu['description'], 0, 100, 'UTF-8')) ?>...</p>
                <a href="game.php?id=<?= (int)$jeu['id_game'] ?>" class="btn">Voir les avis</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun jeu ne correspond à votre recherche.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
