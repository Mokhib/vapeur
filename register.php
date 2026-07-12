<?php
// Page d'inscription : la session doit être vérifiée AVANT toute sortie HTML (header.php inclus).
require_once 'parametrage/param.php';
require_once 'fonction/fonctions.php';
redirigerSiConnecte();

$erreur = '';
$succes = '';

if (isset($_POST['pseudo'])) {
    $pseudo = trim($_POST['pseudo']);
    $email = trim($_POST['email']);
    $motDePasse = $_POST['motDePasse'];
    $confirmationMotDePasse = $_POST['confirmationMotDePasse'];

    $erreur = validerInscription($pdo, $pseudo, $email, $motDePasse, $confirmationMotDePasse);

    if ($erreur === '' && creerUtilisateur($pdo, $pseudo, $email, $motDePasse)) {
        $succes = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
    } elseif ($erreur === '') {
        $erreur = "Erreur lors de l'inscription.";
    }
}

$titrePage = SITE_NAME . ' - Inscription';
include 'header.php';
?>

<h1>Inscription</h1>

<?php if ($erreur) { ?>
    <div class="alert error"><?= htmlspecialchars($erreur) ?></div>
<?php } ?>
<?php if ($succes) { ?>
    <div class="alert success"><?= htmlspecialchars($succes) ?></div>
<?php } ?>

<form method="POST" action="register.php">
    <div class="form-group">
        <label for="pseudo">Pseudo</label>
        <input type="text" id="pseudo" name="pseudo" minlength="3" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="motDePasse">Mot de passe</label>
        <input type="password" id="motDePasse" name="motDePasse" minlength="8" required>
    </div>
    <div class="form-group">
        <label for="confirmationMotDePasse">Confirmer le mot de passe</label>
        <input type="password" id="confirmationMotDePasse" name="confirmationMotDePasse" minlength="8" required>
    </div>
    <button type="submit" class="btn">S'inscrire</button>
    <p class="form-footnote">Déjà inscrit ? <a href="login.php">Se connecter</a></p>
</form>

<?php include 'footer.php'; ?>
