<?php
// Fiche d'un jeu + avis. Toute redirection doit se faire AVANT l'inclusion de header.php.
require_once 'parametrage/param.php';
require_once 'fonction/fonctions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$idJeu = (int)$_GET['id'];
$jeu = obtenirJeuParId($pdo, $idJeu);

if (!$jeu) {
    header('Location: index.php');
    exit();
}

$erreurAvis = '';
$succesAvis = '';

if (isset($_POST['commentaire']) && isset($_SESSION['id_user'])) {
    $note = (int)$_POST['note'];
    $commentaire = trim($_POST['commentaire']);

    if (publierAvis($pdo, (int)$_SESSION['id_user'], $idJeu, $note, $commentaire)) {
        $succesAvis = 'Votre avis a été ajouté avec succès !';
    } else {
        $erreurAvis = 'Veuillez fournir une note entre 1 et 5 et un commentaire.';
    }
}

$listeAvis = obtenirAvisJeu($pdo, $idJeu);

$titrePage = $jeu['title'] . ' - ' . SITE_NAME;
$descriptionPage = 'Avis et notes de la communauté pour ' . $jeu['title'] . ' (' . $jeu['release_year'] . '), par ' . $jeu['developer'] . '.';
include 'header.php';
?>

<h1><?= htmlspecialchars($jeu['title']) ?> (<?= htmlspecialchars($jeu['release_year']) ?>)</h1>
<p><strong>Développeur :</strong> <?= htmlspecialchars($jeu['developer']) ?></p>

<?php if ($jeu['nombre_avis'] > 0) { ?>
    <p class="rating-summary rating-summary--large">
        <span class="rating"><?= genererEtoiles((int)round($jeu['note_moyenne'])) ?></span>
        <span class="rating-value"><?= round((float)$jeu['note_moyenne'], 1) ?>/5</span>
        <span class="rating-count">(<?= (int)$jeu['nombre_avis'] ?> avis)</span>
    </p>
<?php } else { ?>
    <p class="rating-summary rating-summary--empty">Pas encore noté</p>
<?php } ?>

<?php if (!empty($jeu['cover_image']) && file_exists('images/' . $jeu['cover_image'])) { ?>
    <img src="images/<?= htmlspecialchars($jeu['cover_image']) ?>" alt="Jaquette du jeu <?= htmlspecialchars($jeu['title']) ?>" class="game-cover game-cover--detail">
<?php } ?>

<p><strong>Description :</strong> <?= str_replace("\n", '<br>', htmlspecialchars($jeu['description'])) ?></p>

<hr class="section-divider">

<div class="reviews-section">
    <h2>Avis de la communauté</h2>

    <?php if ($erreurAvis) { ?>
        <div class="alert error"><?= htmlspecialchars($erreurAvis) ?></div>
    <?php } ?>
    <?php if ($succesAvis) { ?>
        <div class="alert success"><?= htmlspecialchars($succesAvis) ?></div>
    <?php } ?>

    <?php if (isset($_SESSION['id_user'])) { ?>
        <form method="POST" action="" class="review-form">
            <h3>Laissez votre avis</h3>
            <div class="form-group">
                <label for="note">Note (sur 5)</label>
                <input type="number" id="note" name="note" min="1" max="5" required>
            </div>
            <div class="form-group">
                <label for="commentaire">Commentaire</label>
                <textarea id="commentaire" name="commentaire" required></textarea>
            </div>
            <button type="submit" class="btn">Publier</button>
        </form>
    <?php } else { ?>
        <p><a href="login.php">Connectez-vous</a> pour laisser un avis.</p>
    <?php } ?>

    <div class="reviews-list">
        <?php if (count($listeAvis) > 0) { ?>
            <?php foreach ($listeAvis as $avis) { ?>
                <div class="review-card">
                    <div class="review-header">
                        <strong><?= htmlspecialchars($avis['username']) ?></strong>
                        <span class="rating"><?= genererEtoiles($avis['rating']) ?></span>
                        <span><?= date('d/m/Y', strtotime($avis['created_at'])) ?></span>
                    </div>
                    <p><?= str_replace("\n", '<br>', htmlspecialchars($avis['comment'])) ?></p>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>Aucun avis pour ce jeu pour le moment.</p>
        <?php } ?>
    </div>
</div>

<?php include 'footer.php'; ?>
