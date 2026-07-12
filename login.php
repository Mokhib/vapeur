<?php
// Page de connexion : la session doit être vérifiée AVANT toute sortie HTML (header.php inclus).
require_once 'parametrage/param.php';
require_once 'fonction/fonctions.php';
redirigerSiConnecte();

$erreur = '';

if (isset($_POST['pseudo'])) {
    $pseudo = trim($_POST['pseudo']);
    $motDePasse = $_POST['motDePasse'];

    $utilisateur = verifierIdentifiants($pdo, $pseudo, $motDePasse);

    if ($utilisateur) {
        demarrerSession($utilisateur);
        header('Location: index.php');
        exit();
    } else {
        // Message volontairement générique : ne pas indiquer si c'est le pseudo ou le mot de passe qui est faux.
        $erreur = 'Pseudo ou mot de passe incorrect.';
    }
}

$titrePage = SITE_NAME . ' - Connexion';
include 'header.php';
?>

<h1>Connexion</h1>

<?php if ($erreur) { ?>
    <div class="alert error"><?= htmlspecialchars($erreur) ?></div>
<?php } ?>

<form method="POST" action="login.php">
    <div class="form-group">
        <label for="pseudo">Pseudo</label>
        <input type="text" id="pseudo" name="pseudo" required>
    </div>
    <div class="form-group">
        <label for="motDePasse">Mot de passe</label>
        <input type="password" id="motDePasse" name="motDePasse" required>
    </div>
    <button type="submit" class="btn">Se connecter</button>
    <p class="form-footnote">Pas de compte ? <a href="register.php">S'inscrire</a></p>
</form>

<?php include 'footer.php'; ?>
