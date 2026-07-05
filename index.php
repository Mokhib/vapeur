<?php include 'header.php'; ?>

<h1>Bienvenue sur <?= SITE_NAME ?></h1>
<p>Découvrez notre sélection de jeux d'aventure point-and-click.</p>

<div class="games-grid">
    <?php
    // Requête pour récupérer les jeux depuis la table games
    $query = "SELECT id_game, title, description, release_year, developer FROM games ORDER BY release_year DESC";
    // Utilisation de la connexion procédurale mysqli
    $result = mysqli_query($connexion, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($game = mysqli_fetch_assoc($result)) {
            ?>
            <div class="game-card">
                <h3><?= htmlspecialchars($game['title']) ?> (<?= htmlspecialchars($game['release_year']) ?>)</h3>
                <p><strong>Studio :</strong> <?= htmlspecialchars($game['developer']) ?></p>
                <p><?= htmlspecialchars(substr($game['description'], 0, 100)) ?>...</p>
                <!-- Lien vers la page de détail du jeu via id_game -->
                <a href="game.php?id=<?= $game['id_game'] ?>" class="btn">Voir les avis</a>
            </div>
            <?php
        }
    } else {
        echo "<p>Aucun jeu n'a été trouvé dans la base de données.</p>";
    }
    ?>
</div>

<?php include 'footer.php'; ?>