<?php 
include 'header.php'; 

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<p>Jeu introuvable.</p>");
}
$id_game = (int)$_GET['id'];

// Gestion de l'ajout d'un avis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['id_user'])) {
    $rating = (int)$_POST['rating'];
    $comment = mysqli_real_escape_string($connexion, trim($_POST['comment']));
    
    // rating est un tinyint(1) dans la base[cite: 7]
    if ($rating >= 1 && $rating <= 5 && !empty($comment)) {
        $id_user = $_SESSION['id_user'];
        // Insertion dans la table reviews[cite: 7]
        $insertQuery = "INSERT INTO reviews (id_user, id_game, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($connexion, $insertQuery);
        mysqli_stmt_bind_param($stmt, "iiis", $id_user, $id_game, $rating, $comment);
        mysqli_stmt_execute($stmt);
        echo "<div class='alert'>Votre avis a été ajouté avec succès !</div>";
    } else {
        echo "<div class='alert error'>Veuillez fournir une note entre 1 et 5 et un commentaire.</div>";
    }
}

// Récupération des informations du jeu[cite: 7]
$gameQuery = "SELECT * FROM games WHERE id_game = ?";
$stmt = mysqli_prepare($connexion, $gameQuery);
mysqli_stmt_bind_param($stmt, "i", $id_game);
mysqli_stmt_execute($stmt);
$gameResult = mysqli_stmt_get_result($stmt);
$game = mysqli_fetch_assoc($gameResult);

if (!$game) {
    die("<p>Jeu introuvable.</p>");
}
?>

<h1><?= htmlspecialchars($game['title']) ?> (<?= htmlspecialchars($game['release_year']) ?>)</h1>
<p><strong>Développeur :</strong> <?= htmlspecialchars($game['developer']) ?></p>
<p><strong>Description :</strong> <?= nl2br(htmlspecialchars($game['description'])) ?></p>

<hr style="margin: 2rem 0; border-color: #333;">

<div class="reviews-section">
    <h2>Avis de la communauté</h2>
    
    <?php if (isset($_SESSION['id_user'])): ?>
        <form method="POST" action="" style="margin: 1rem 0; max-width: 100%; padding: 1rem;">
            <h3>Laissez votre avis</h3>
            <div class="form-group">
                <label>Note (sur 5)</label>
                <!-- La note doit être compatible avec le tinyint(1)[cite: 7] -->
                <input type="number" name="rating" min="1" max="5" required>
            </div>
            <div class="form-group">
                <label>Commentaire</label>
                <textarea name="comment" required></textarea>
            </div>
            <button type="submit" class="btn">Publier</button>
        </form>
    <?php else: ?>
        <p><a href="login.php">Connectez-vous</a> pour laisser un avis.</p>
    <?php endif; ?>

    <div style="margin-top: 2rem;">
        <?php
        // Récupération des avis associés via id_game et jointure avec users pour le username[cite: 7]
        $reviewQuery = "
            SELECT r.rating, r.comment, r.created_at, u.username 
            FROM reviews r 
            JOIN users u ON r.id_user = u.id_user 
            WHERE r.id_game = ? 
            ORDER BY r.created_at DESC
        ";
        $stmtRev = mysqli_prepare($connexion, $reviewQuery);
        mysqli_stmt_bind_param($stmtRev, "i", $id_game);
        mysqli_stmt_execute($stmtRev);
        $reviewsResult = mysqli_stmt_get_result($stmtRev);

        if (mysqli_num_rows($reviewsResult) > 0) {
            while ($review = mysqli_fetch_assoc($reviewsResult)) {
                ?>
                <div class="review-card">
                    <div class="review-header">
                        <strong><?= htmlspecialchars($review['username']) ?></strong>
                        <span class="rating"><?= str_repeat("★", $review['rating']) ?><?= str_repeat("☆", 5 - $review['rating']) ?></span>
                        <span><?= date('d/m/Y', strtotime($review['created_at'])) ?></span>
                    </div>
                    <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                </div>
                <?php
            }
        } else {
            echo "<p>Aucun avis pour ce jeu pour le moment.</p>";
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>