<?php
// Page profil : informations du compte connecté + changement de mot de passe.
require_once 'parametrage/param.php';
require_once 'fonction/fonctions.php';
exigerConnexion();

$erreur = '';
$succes = '';

if (isset($_POST['motDePasseActuel'])) {
    $motDePasseActuel = $_POST['motDePasseActuel'];
    $nouveauMotDePasse = $_POST['nouveauMotDePasse'];
    $confirmationNouveauMotDePasse = $_POST['confirmationNouveauMotDePasse'];

    $utilisateur = trouverUtilisateur($pdo, $_SESSION['username']);
    $erreur = validerChangementMotDePasse($motDePasseActuel, $nouveauMotDePasse, $confirmationNouveauMotDePasse, $utilisateur);

    if ($erreur === '' && modifierMotDePasse($pdo, (int)$utilisateur['id_user'], $nouveauMotDePasse)) {
        $succes = 'Mot de passe mis à jour avec succès.';
    } elseif ($erreur === '') {
        $erreur = 'Erreur lors de la mise à jour du mot de passe.';
    }
}

$utilisateur = trouverUtilisateur($pdo, $_SESSION['username']);

$titrePage = SITE_NAME . ' - Profil';
include 'header.php';
?>

<h1>Mon profil</h1>

<section class="profile-section">
    <h2>Informations du compte</h2>
    <dl class="profile-info">
        <dt>Pseudo</dt>
        <dd><?= htmlspecialchars($utilisateur['username']) ?></dd>
        <dt>Email</dt>
        <dd><?= htmlspecialchars($utilisateur['email']) ?></dd>
        <dt>Rôle</dt>
        <dd><span class="badge badge-<?= htmlspecialchars($utilisateur['role']) ?>"><?= htmlspecialchars($utilisateur['role']) ?></span></dd>
        <dt>Membre depuis</dt>
        <dd><?= date('d/m/Y', strtotime($utilisateur['created_at'])) ?></dd>
    </dl>
</section>

<section class="profile-section">
    <h2>Changer mon mot de passe</h2>

    <?php if ($erreur) { ?>
        <div class="alert error"><?= htmlspecialchars($erreur) ?></div>
    <?php } ?>
    <?php if ($succes) { ?>
        <div class="alert success"><?= htmlspecialchars($succes) ?></div>
    <?php } ?>

    <form method="POST" action="profil.php">
        <div class="form-group">
            <label for="motDePasseActuel">Mot de passe actuel</label>
            <input type="password" id="motDePasseActuel" name="motDePasseActuel" required>
        </div>
        <div class="form-group">
            <label for="nouveauMotDePasse">Nouveau mot de passe</label>
            <input type="password" id="nouveauMotDePasse" name="nouveauMotDePasse" minlength="8" required>
        </div>
        <div class="form-group">
            <label for="confirmationNouveauMotDePasse">Confirmer le nouveau mot de passe</label>
            <input type="password" id="confirmationNouveauMotDePasse" name="confirmationNouveauMotDePasse" minlength="8" required>
        </div>
        <button type="submit" class="btn">Mettre à jour</button>
    </form>
</section>

<?php include 'footer.php'; ?>
