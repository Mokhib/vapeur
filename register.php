<?php 
include 'header.php'; 

if (isset($_SESSION['id_user'])) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Vérifier si l'utilisateur existe déjà
    $checkQuery = "SELECT id_user FROM users WHERE username = ? OR email = ?";
    $stmtCheck = mysqli_prepare($connexion, $checkQuery);
    mysqli_stmt_bind_param($stmtCheck, "ss", $username, $email);
    mysqli_stmt_execute($stmtCheck);
    $resultCheck = mysqli_stmt_get_result($stmtCheck);

    if (mysqli_num_rows($resultCheck) > 0) {
        $error = "Ce nom d'utilisateur ou cet email est déjà utilisé.";
    } else {
        // Hachage du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insertion dans la table users[cite: 7]
        $insertQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmtInsert = mysqli_prepare($connexion, $insertQuery);
        mysqli_stmt_bind_param($stmtInsert, "sss", $username, $email, $hashed_password);
        
        if (mysqli_stmt_execute($stmtInsert)) {
            $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        } else {
            $error = "Erreur lors de l'inscription.";
        }
    }
}
?>

<h1>Inscription</h1>

<?php if ($error): ?>
    <div class="alert error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" action="register.php">
    <div class="form-group">
        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn">S'inscrire</button>
    <p style="margin-top: 1rem;">Déjà inscrit ? <a href="login.php">Se connecter</a></p>
</form>

<?php include 'footer.php'; ?>