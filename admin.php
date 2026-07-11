<?php
// Panel d'administration : gestion des comptes et du catalogue. Réservé aux administrateurs.
require_once 'parametrage/param.php';
require_once 'fonction/fonctions.php';
exigerAdmin();

$messageUtilisateur = '';
$messageJeu = '';

// Jeu en cours de modification, si l'admin a cliqué sur « Modifier » dans le catalogue
$jeuEnEdition = null;
if (isset($_GET['editerJeu']) && is_numeric($_GET['editerJeu'])) {
    $jeuEnEdition = obtenirJeuParId($pdo, (int)$_GET['editerJeu']) ?: null;
}

// --- Actions sur les comptes utilisateurs (promouvoir / rétrograder / supprimer) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actionUtilisateur'])) {
    $messageUtilisateur = traiterActionUtilisateur($pdo, (int)$_SESSION['id_user'], $_POST['actionUtilisateur'], (int)$_POST['idUtilisateur']);
}

// --- Ajout d'un jeu au catalogue, avec jaquette optionnelle ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouterJeu'])) {
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $anneeSortie = (int)$_POST['anneeSortie'];
    $developpeur = trim($_POST['developpeur']);

    if (!validerJeu($titre, $description, $developpeur, $anneeSortie)) {
        $messageJeu = 'Merci de remplir correctement tous les champs du jeu.';
    } else {
        $jaquette = traiterJaquette($_FILES['jaquette'] ?? null);
        if (ajouterJeu($pdo, $titre, $description, $anneeSortie, $developpeur, $jaquette)) {
            $messageJeu = 'Jeu ajouté au catalogue.';
        } else {
            $messageJeu = "Erreur lors de l'ajout du jeu.";
        }
    }
}

// --- Modification d'un jeu existant ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifierJeu'])) {
    $idJeu = (int)$_POST['idJeu'];
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $anneeSortie = (int)$_POST['anneeSortie'];
    $developpeur = trim($_POST['developpeur']);

    if (!validerJeu($titre, $description, $developpeur, $anneeSortie)) {
        $messageJeu = 'Merci de remplir correctement tous les champs du jeu.';
        $jeuEnEdition = obtenirJeuParId($pdo, $idJeu) ?: null;
    } else {
        $nouvelleJaquette = traiterJaquette($_FILES['jaquette'] ?? null);
        if (modifierJeu($pdo, $idJeu, $titre, $description, $anneeSortie, $developpeur, $nouvelleJaquette)) {
            $messageJeu = 'Jeu modifié.';
        } else {
            $messageJeu = "Erreur lors de la modification du jeu.";
            $jeuEnEdition = obtenirJeuParId($pdo, $idJeu) ?: null;
        }
    }
}

// --- Suppression d'un jeu ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimerJeu'])) {
    supprimerJeu($pdo, (int)$_POST['idJeu']);
    $messageJeu = 'Jeu supprimé du catalogue.';
}

$utilisateurs = obtenirUtilisateurs($pdo);
$jeux = obtenirJeux($pdo);

$titrePage = SITE_NAME . ' - Administration';
include 'header.php';
?>

<h1>Administration</h1>
<p>Gérez les comptes utilisateurs et le catalogue de jeux de <?= SITE_NAME ?>.</p>

<section class="admin-section">
    <h2>Comptes utilisateurs</h2>
    <?php if ($messageUtilisateur): ?><div class="alert"><?= htmlspecialchars($messageUtilisateur) ?></div><?php endif; ?>

    <div class="table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Pseudo</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $utilisateur): ?>
                    <tr>
                        <td><?= htmlspecialchars($utilisateur['username']) ?></td>
                        <td><?= htmlspecialchars($utilisateur['email']) ?></td>
                        <td><span class="badge badge-<?= htmlspecialchars($utilisateur['role']) ?>"><?= htmlspecialchars($utilisateur['role']) ?></span></td>
                        <td><?= date('d/m/Y', strtotime($utilisateur['created_at'])) ?></td>
                        <td class="admin-actions">
                            <?php if ((int)$utilisateur['id_user'] !== (int)$_SESSION['id_user']): ?>
                                <?php if ($utilisateur['role'] === 'user'): ?>
                                    <form method="POST" action="admin.php">
                                        <input type="hidden" name="idUtilisateur" value="<?= (int)$utilisateur['id_user'] ?>">
                                        <input type="hidden" name="actionUtilisateur" value="promouvoir">
                                        <button type="submit" class="btn btn-small">Promouvoir admin</button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" action="admin.php">
                                        <input type="hidden" name="idUtilisateur" value="<?= (int)$utilisateur['id_user'] ?>">
                                        <input type="hidden" name="actionUtilisateur" value="retrograder">
                                        <button type="submit" class="btn btn-small">Rétrograder</button>
                                    </form>
                                <?php endif; ?>
                                <form method="POST" action="admin.php" data-confirm="Supprimer définitivement ce compte ?">
                                    <input type="hidden" name="idUtilisateur" value="<?= (int)$utilisateur['id_user'] ?>">
                                    <input type="hidden" name="actionUtilisateur" value="supprimer">
                                    <button type="submit" class="btn btn-small btn-danger">Supprimer</button>
                                </form>
                            <?php else: ?>
                                <span class="muted">Votre compte</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="admin-section">
    <h2><?= $jeuEnEdition ? 'Modifier un jeu' : 'Ajouter un jeu' ?></h2>
    <?php if ($messageJeu): ?><div class="alert"><?= htmlspecialchars($messageJeu) ?></div><?php endif; ?>
    <form method="POST" action="admin.php" enctype="multipart/form-data">
        <?php if ($jeuEnEdition): ?>
            <input type="hidden" name="idJeu" value="<?= (int)$jeuEnEdition['id_game'] ?>">
        <?php endif; ?>
        <div class="form-group">
            <label for="titre">Titre</label>
            <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($jeuEnEdition['title'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="developpeur">Studio</label>
            <input type="text" id="developpeur" name="developpeur" value="<?= htmlspecialchars($jeuEnEdition['developer'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="anneeSortie">Année de sortie</label>
            <input type="number" id="anneeSortie" name="anneeSortie" min="1970" max="2100" value="<?= htmlspecialchars($jeuEnEdition['release_year'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($jeuEnEdition['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="jaquette">Jaquette (jpg, png, gif ou webp, 2 Mo max)<?= $jeuEnEdition ? ' — laisser vide pour conserver l\'actuelle' : '' ?></label>
            <input type="file" id="jaquette" name="jaquette" accept="image/png, image/jpeg, image/gif, image/webp">
        </div>
        <?php if ($jeuEnEdition): ?>
            <button type="submit" name="modifierJeu" value="1" class="btn">Enregistrer les modifications</button>
            <a href="admin.php" class="btn btn-secondary">Annuler</a>
        <?php else: ?>
            <button type="submit" name="ajouterJeu" value="1" class="btn">Ajouter le jeu</button>
        <?php endif; ?>
    </form>
</section>

<section class="admin-section">
    <h2>Catalogue actuel</h2>
    <div class="table-wrap">
        <table class="admin-table">
            <thead><tr><th>Titre</th><th>Année</th><th>Studio</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($jeux as $jeu): ?>
                    <tr>
                        <td><?= htmlspecialchars($jeu['title']) ?></td>
                        <td><?= htmlspecialchars($jeu['release_year']) ?></td>
                        <td><?= htmlspecialchars($jeu['developer']) ?></td>
                        <td class="admin-actions">
                            <a href="admin.php?editerJeu=<?= (int)$jeu['id_game'] ?>" class="btn btn-small">Modifier</a>
                            <form method="POST" action="admin.php" data-confirm="Supprimer ce jeu et tous ses avis ?">
                                <input type="hidden" name="idJeu" value="<?= (int)$jeu['id_game'] ?>">
                                <button type="submit" name="supprimerJeu" value="1" class="btn btn-small btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include 'footer.php'; ?>
