<?php
require_once 'parametrage/param.php';
require_once 'fonction/fonctions.php';

$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : '';
$tri = isset($_GET['tri']) ? $_GET['tri'] : 'recent';

$anneesExtremes = obtenirAnneesExtremes($pdo);
$anneeMinCatalogue = (int)$anneesExtremes['anneeMin'];
$anneeMaxCatalogue = (int)$anneesExtremes['anneeMax'];

$anneeMin = isset($_GET['anneeMin']) && is_numeric($_GET['anneeMin']) ? (int)$_GET['anneeMin'] : $anneeMinCatalogue;
$anneeMax = isset($_GET['anneeMax']) && is_numeric($_GET['anneeMax']) ? (int)$_GET['anneeMax'] : $anneeMaxCatalogue;

$supportsSelectionnes = isset($_GET['support']) && is_array($_GET['support']) ? $_GET['support'] : array();
$editeursSelectionnes = isset($_GET['editeur']) && is_array($_GET['editeur']) ? $_GET['editeur'] : array();

$jeux = obtenirJeux($pdo, $recherche, $tri, $anneeMin, $anneeMax, $supportsSelectionnes, $editeursSelectionnes);
$editeursDisponibles = obtenirEditeursDistincts($pdo);

$titrePage = SITE_NAME . ' - Accueil';
$descriptionPage = "Parcourez le catalogue de jeux d'aventure point-and-click de Vapeur et découvrez les avis de la communauté.";
include 'header.php';
?>

<div class="page-layout">
    <aside class="filter-panel">
        <form method="GET" action="index.php" id="formulaireFiltres">
            <h2>Filtres</h2>
            <button type="submit" class="btn filter-btn-top">Filtrer</button>

            <div class="filter-group">
                <label for="recherche">Recherche</label>
                <input type="text" id="recherche" name="recherche" placeholder="Titre ou studio..." value="<?= htmlspecialchars($recherche) ?>">
            </div>

            <div class="filter-group">
                <label for="tri">Trier par</label>
                <select id="tri" name="tri">
                    <option value="recent" <?= $tri === 'recent' ? 'selected' : '' ?>>Plus récents</option>
                    <option value="ancien" <?= $tri === 'ancien' ? 'selected' : '' ?>>Plus anciens</option>
                    <option value="titre" <?= $tri === 'titre' ? 'selected' : '' ?>>Titre (A-Z)</option>
                </select>
            </div>

            <div class="filter-group filter-group--range">
                <label>Année de sortie</label>
                <div class="range-inputs">
                    <input type="number" id="anneeMinTexte" name="anneeMin" class="range-number" min="<?= $anneeMinCatalogue ?>" max="<?= $anneeMaxCatalogue ?>" value="<?= $anneeMin ?>" aria-label="Année minimum">
                    <span class="range-sep">–</span>
                    <input type="number" id="anneeMaxTexte" name="anneeMax" class="range-number" min="<?= $anneeMinCatalogue ?>" max="<?= $anneeMaxCatalogue ?>" value="<?= $anneeMax ?>" aria-label="Année maximum">
                </div>
                <div class="dual-range">
                    <input type="range" id="anneeMin" min="<?= $anneeMinCatalogue ?>" max="<?= $anneeMaxCatalogue ?>" value="<?= $anneeMin ?>" aria-hidden="true" tabindex="-1">
                    <input type="range" id="anneeMax" min="<?= $anneeMinCatalogue ?>" max="<?= $anneeMaxCatalogue ?>" value="<?= $anneeMax ?>" aria-hidden="true" tabindex="-1">
                </div>
                <div class="range-bounds">
                    <span><?= $anneeMinCatalogue ?></span>
                    <span><?= $anneeMaxCatalogue ?></span>
                </div>
            </div>

            <fieldset class="filter-group">
                <legend>Support</legend>
                <?php foreach (listeSupports() as $support) { ?>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="support[]" value="<?= htmlspecialchars($support) ?>" <?= in_array($support, $supportsSelectionnes, true) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($support) ?>
                    </label>
                <?php } ?>
            </fieldset>

            <fieldset class="filter-group">
                <legend>Éditeur</legend>
                <?php foreach ($editeursDisponibles as $editeur) { ?>
                    <label class="filter-checkbox">
                        <input type="checkbox" name="editeur[]" value="<?= htmlspecialchars($editeur) ?>" <?= in_array($editeur, $editeursSelectionnes, true) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($editeur) ?>
                    </label>
                <?php } ?>
            </fieldset>

            <button type="submit" class="btn">Filtrer</button>
            <a href="index.php" class="btn btn-secondary">Réinitialiser</a>
        </form>
    </aside>

    <div class="page-main">
        <div class="page-intro">
            <h1>Bienvenue sur <?= SITE_NAME ?></h1>
            <p>La jauge communautaire des jeux d'aventure point-and-click : explorez le catalogue, comparez les avis et laissez le vôtre.</p>
        </div>
        <hr class="section-divider">

        <p class="results-count"><?= count($jeux) ?> jeu<?= count($jeux) > 1 ? 'x' : '' ?> trouvé<?= count($jeux) > 1 ? 's' : '' ?></p>

        <div class="games-grid">
            <?php if (count($jeux) > 0) { ?>
                <?php foreach ($jeux as $jeu) { ?>
                    <div class="game-card">
                        <?php if (!empty($jeu['cover_image']) && file_exists(DOSSIER_SITE . '/images/' . $jeu['cover_image'])) { ?>
                            <img src="images/<?= htmlspecialchars($jeu['cover_image']) ?>" alt="Jaquette du jeu <?= htmlspecialchars($jeu['title']) ?>" class="game-cover">
                        <?php } else { ?>
                            <div class="game-cover game-cover--placeholder" aria-hidden="true"><?= htmlspecialchars($jeu['title']) ?></div>
                        <?php } ?>
                        <h2><?= htmlspecialchars($jeu['title']) ?> (<?= htmlspecialchars($jeu['release_year']) ?>)</h2>
                        <?php if ($jeu['nombre_avis'] > 0) { ?>
                            <p class="rating-summary">
                                <span class="rating"><?= genererEtoiles((int)round($jeu['note_moyenne'])) ?></span>
                                <span class="rating-value"><?= round((float)$jeu['note_moyenne'], 1) ?>/5</span>
                                <span class="rating-count">(<?= (int)$jeu['nombre_avis'] ?> avis)</span>
                            </p>
                        <?php } else { ?>
                            <p class="rating-summary rating-summary--empty">Pas encore noté</p>
                        <?php } ?>
                        <p><strong>Studio :</strong> <?= htmlspecialchars($jeu['developer']) ?></p>
                        <p class="game-support"><?= htmlspecialchars(str_replace(',', ' · ', $jeu['support'])) ?></p>
                        <p><?= htmlspecialchars(mb_substr($jeu['description'], 0, 100, 'UTF-8')) ?>...</p>
                        <a href="game.php?id=<?= (int)$jeu['id_game'] ?>" class="btn">Voir les avis</a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>Aucun jeu ne correspond à ces filtres.</p>
            <?php } ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
