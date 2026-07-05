<?php 
include 'header.php'; 

if (isset($_SESSION['id_user'])) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Vérification dans la table users[cite: 7]
    $query = "SELECT id_user, username, password, role FROM users WHERE username = ?";
    $stmt = mysqli_prepare($connexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        // Le mot de passe dans la base est haché via password_hash[cite: 7]
        if (password_verify($password, $user['password'])) {
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Récupération du rôle ('user' ou 'admin')[cite: 7]
            header('Location: index.php');
            exit();
        } else {
            $error = 'Mot de passe incorrect.';
        }
    } else {
        $error = 'Nom d\'utilisateur introuvable.';
    }
}
?>

<h1>Connexion</h1>

<?php if ($error): ?>
    <div class="alert error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="login.php">
    <div class="form-group">
        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn">Se connecter</button>
    <p style="margin-top: 1rem;">Pas de compte ? <a href="register.php">S'inscrire</a></p>
</form>

<?php include 'footer.php'; ?>