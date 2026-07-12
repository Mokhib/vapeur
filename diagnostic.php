<?php
// Script de diagnostic temporaire — à supprimer du serveur une fois le problème résolu.
require_once 'parametrage/param.php';

echo '<pre>';

echo "La table games a-t-elle une colonne support : ";
$colonnes = $pdo->query("SHOW COLUMNS FROM games")->fetchAll(PDO::FETCH_COLUMN);
echo (in_array('support', $colonnes, true) ? 'OUI' : 'NON') . "\n";
echo "Colonnes présentes : " . implode(', ', $colonnes) . "\n\n";

echo "cover_image stocké en base VS fichier réellement présent :\n\n";
$requete = $pdo->query('SELECT id_game, title, cover_image FROM games ORDER BY id_game LIMIT 6');
while ($jeu = $requete->fetch()) {
    $chemin = DOSSIER_SITE . '/images/' . $jeu['cover_image'];
    echo "#" . $jeu['id_game'] . " " . $jeu['title'] . "\n";
    echo "  cover_image en base : '" . $jeu['cover_image'] . "'\n";
    echo "  file_exists()       : " . (file_exists($chemin) ? 'OUI' : 'NON') . "\n";
    echo "  chemin testé        : " . $chemin . "\n\n";
}

echo "</pre>";
