<?php
// Regroupe toutes les fonctions utilisées par le site Vapeur.
// Chaque fonction reçoit la connexion PDO ($pdo) en paramètre plutôt que de la lire en variable globale.

// --- Jeux ------------------------------------------------------------------

// Renvoie la liste des jeux, avec recherche, tri et filtres (année, support, éditeur) optionnels.
// Chaque jeu porte sa note moyenne (note_moyenne) et son nombre d'avis (nombre_avis).
function obtenirJeux(PDO $pdo, string $recherche = '', string $tri = 'recent', ?int $anneeMin = null, ?int $anneeMax = null, array $supports = array(), array $editeurs = array()): array
{
    $sql = 'SELECT g.id_game, g.title, g.description, g.release_year, g.developer, g.support, g.cover_image,
                   AVG(r.rating) AS note_moyenne, COUNT(r.id_review) AS nombre_avis
            FROM games g
            LEFT JOIN reviews r ON r.id_game = g.id_game';
    $parametres = array();
    $conditions = array();

    if ($recherche !== '') {
        $conditions[] = '(g.title LIKE :recherche OR g.developer LIKE :recherche)';
        $parametres['recherche'] = '%' . $recherche . '%';
    }

    if ($anneeMin !== null) {
        $conditions[] = 'g.release_year >= :anneeMin';
        $parametres['anneeMin'] = $anneeMin;
    }

    if ($anneeMax !== null) {
        $conditions[] = 'g.release_year <= :anneeMax';
        $parametres['anneeMax'] = $anneeMax;
    }

    if (count($supports) > 0) {
        $sousCondition = '';
        $i = 0;
        foreach ($supports as $support) {
            if ($i > 0) {
                $sousCondition .= ' OR ';
            }
            $cle = 'support' . $i;
            $sousCondition .= 'g.support LIKE :' . $cle;
            $parametres[$cle] = '%' . $support . '%';
            $i++;
        }
        $conditions[] = '(' . $sousCondition . ')';
    }

    if (count($editeurs) > 0) {
        $sousCondition = '';
        $i = 0;
        foreach ($editeurs as $editeur) {
            if ($i > 0) {
                $sousCondition .= ' OR ';
            }
            $cle = 'editeur' . $i;
            $sousCondition .= 'g.developer = :' . $cle;
            $parametres[$cle] = $editeur;
            $i++;
        }
        $conditions[] = '(' . $sousCondition . ')';
    }

    for ($i = 0; $i < count($conditions); $i++) {
        $sql .= ($i === 0 ? ' WHERE ' : ' AND ') . $conditions[$i];
    }

    $sql .= ' GROUP BY g.id_game';

    switch ($tri) {
        case 'titre':
            $sql .= ' ORDER BY g.title ASC';
            break;
        case 'ancien':
            $sql .= ' ORDER BY g.release_year ASC';
            break;
        case 'recent':
        default:
            $sql .= ' ORDER BY g.release_year DESC';
            break;
    }

    $requete = $pdo->prepare($sql);
    $requete->execute($parametres);

    $jeux = array();
    while ($ligne = $requete->fetch()) {
        $jeux[] = $ligne;
    }
    return $jeux;
}

// Renvoie l'année de sortie la plus ancienne et la plus récente du catalogue (bornes du filtre).
function obtenirAnneesExtremes(PDO $pdo): array
{
    $requete = $pdo->query('SELECT MIN(release_year) AS anneeMin, MAX(release_year) AS anneeMax FROM games');
    return $requete->fetch();
}

// Liste fixe des supports proposés dans le filtre.
function listeSupports(): array
{
    return array('PC', 'Console', 'Mobile');
}

// Renvoie la liste des éditeurs distincts présents dans le catalogue, pour le filtre.
function obtenirEditeursDistincts(PDO $pdo): array
{
    $requete = $pdo->query('SELECT DISTINCT developer FROM games ORDER BY developer ASC');

    $editeurs = array();
    while ($ligne = $requete->fetch()) {
        $editeurs[] = $ligne['developer'];
    }
    return $editeurs;
}

// Renvoie un jeu précis à partir de son identifiant, avec sa note moyenne, ou false s'il n'existe pas.
function obtenirJeuParId(PDO $pdo, int $idJeu)
{
    $requete = $pdo->prepare(
        'SELECT g.*, AVG(r.rating) AS note_moyenne, COUNT(r.id_review) AS nombre_avis
         FROM games g
         LEFT JOIN reviews r ON r.id_game = g.id_game
         WHERE g.id_game = :idJeu
         GROUP BY g.id_game'
    );
    $requete->execute(array('idJeu' => $idJeu));
    return $requete->fetch();
}

// Ajoute un jeu au catalogue (réservé au panel administrateur).
function ajouterJeu(PDO $pdo, string $titre, string $description, int $anneeSortie, string $developpeur, string $support, ?string $jaquette): bool
{
    $requete = $pdo->prepare(
        'INSERT INTO games (title, description, release_year, developer, support, cover_image)
         VALUES (:titre, :description, :anneeSortie, :developpeur, :support, :jaquette)'
    );
    return $requete->execute(array(
        'titre' => $titre,
        'description' => $description,
        'anneeSortie' => $anneeSortie,
        'developpeur' => $developpeur,
        'support' => $support,
        'jaquette' => $jaquette,
    ));
}

// Modifie un jeu existant. Si aucune nouvelle jaquette n'est fournie, l'ancienne est conservée.
function modifierJeu(PDO $pdo, int $idJeu, string $titre, string $description, int $anneeSortie, string $developpeur, string $support, ?string $nouvelleJaquette): bool
{
    if ($nouvelleJaquette !== null) {
        $requete = $pdo->prepare(
            'UPDATE games SET title = :titre, description = :description, release_year = :anneeSortie,
             developer = :developpeur, support = :support, cover_image = :jaquette WHERE id_game = :idJeu'
        );
        return $requete->execute(array(
            'titre' => $titre,
            'description' => $description,
            'anneeSortie' => $anneeSortie,
            'developpeur' => $developpeur,
            'support' => $support,
            'jaquette' => $nouvelleJaquette,
            'idJeu' => $idJeu,
        ));
    }

    $requete = $pdo->prepare(
        'UPDATE games SET title = :titre, description = :description, release_year = :anneeSortie,
         developer = :developpeur, support = :support WHERE id_game = :idJeu'
    );
    return $requete->execute(array(
        'titre' => $titre,
        'description' => $description,
        'anneeSortie' => $anneeSortie,
        'developpeur' => $developpeur,
        'support' => $support,
        'idJeu' => $idJeu,
    ));
}

// Joint un tableau de supports cochés en une chaîne séparée par des virgules (ex. "PC,Console").
function joindreSupports(array $supports): string
{
    $resultat = '';
    $i = 0;
    foreach ($supports as $support) {
        if ($i > 0) {
            $resultat .= ',';
        }
        $resultat .= $support;
        $i++;
    }
    return $resultat;
}

// Supprime un jeu (et ses avis, via ON DELETE CASCADE) du catalogue.
function supprimerJeu(PDO $pdo, int $idJeu): bool
{
    $requete = $pdo->prepare('DELETE FROM games WHERE id_game = :idJeu');
    return $requete->execute(array('idJeu' => $idJeu));
}

// Vérifie que les champs obligatoires d'un jeu sont correctement renseignés.
function validerJeu(string $titre, string $description, string $developpeur, int $anneeSortie): bool
{
    return $titre !== '' && $description !== '' && $developpeur !== '' && $anneeSortie >= 1970;
}

// Traite l'upload d'une jaquette de jeu : vérifie extension et taille avant de déplacer le fichier.
// Renvoie le nom de fichier stocké, ou null si aucun fichier valide n'a été fourni.
function traiterJaquette(?array $fichier): ?string
{
    if (!$fichier || $fichier['error'] !== 0) {
        return null;
    }

    $infos = pathinfo($fichier['name']);
    $extension = isset($infos['extension']) ? strtolower($infos['extension']) : '';
    $extensionsAutorisees = array('jpg', 'jpeg', 'png', 'gif', 'webp');

    if (!in_array($extension, $extensionsAutorisees, true) || $fichier['size'] > 2000000) {
        return null;
    }

    // Préfixe horodaté pour éviter d'écraser un fichier existant portant le même nom.
    $nomFichier = date('YmdHis') . '_' . basename($fichier['name']);
    return move_uploaded_file($fichier['tmp_name'], DOSSIER_SITE . '/images/' . $nomFichier) ? $nomFichier : null;
}

// --- Avis --------------------------------------------------------------------

// Renvoie les avis d'un jeu, du plus récent au plus ancien, avec le pseudo de l'auteur.
function obtenirAvisJeu(PDO $pdo, int $idJeu): array
{
    $requete = $pdo->prepare(
        'SELECT r.rating, r.comment, r.created_at, u.username
         FROM reviews r
         JOIN users u ON r.id_user = u.id_user
         WHERE r.id_game = :idJeu
         ORDER BY r.created_at DESC'
    );
    $requete->execute(array('idJeu' => $idJeu));

    $avis = array();
    while ($ligne = $requete->fetch()) {
        $avis[] = $ligne;
    }
    return $avis;
}

// Génère une représentation textuelle d'une note sous forme d'étoiles pleines et vides.
function genererEtoiles(int $note): string
{
    $etoiles = '';
    for ($i = 1; $i <= 5; $i++) {
        $etoiles .= ($i <= $note) ? '★' : '☆';
    }
    return $etoiles;
}

// Enregistre un nouvel avis (note + commentaire) sur un jeu.
function ajouterAvis(PDO $pdo, int $idUtilisateur, int $idJeu, int $note, string $commentaire): bool
{
    $requete = $pdo->prepare(
        'INSERT INTO reviews (id_user, id_game, rating, comment)
         VALUES (:idUtilisateur, :idJeu, :note, :commentaire)'
    );
    return $requete->execute(array(
        'idUtilisateur' => $idUtilisateur,
        'idJeu' => $idJeu,
        'note' => $note,
        'commentaire' => $commentaire,
    ));
}

// Valide puis enregistre un avis ; renvoie true si l'avis a bien été publié.
function publierAvis(PDO $pdo, int $idUtilisateur, int $idJeu, int $note, string $commentaire): bool
{
    if ($note < 1 || $note > 5 || $commentaire === '') {
        return false;
    }
    return ajouterAvis($pdo, $idUtilisateur, $idJeu, $note, $commentaire);
}

// --- Utilisateurs --------------------------------------------------------------

// Recherche un utilisateur par son pseudo (utilisé à la connexion).
function trouverUtilisateur(PDO $pdo, string $pseudo)
{
    $requete = $pdo->prepare('SELECT * FROM users WHERE username = :pseudo');
    $requete->execute(array('pseudo' => $pseudo));
    return $requete->fetch();
}

// Indique si un pseudo ou un email est déjà pris.
function utilisateurExiste(PDO $pdo, string $pseudo, string $email): bool
{
    $requete = $pdo->prepare('SELECT id_user FROM users WHERE username = :pseudo OR email = :email');
    $requete->execute(array('pseudo' => $pseudo, 'email' => $email));
    return $requete->fetch() !== false;
}

// Crée un nouvel utilisateur.
function creerUtilisateur(PDO $pdo, string $pseudo, string $email, string $motDePasse): bool
{
    $requete = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:pseudo, :email, :motDePasse)');
    return $requete->execute(array('pseudo' => $pseudo, 'email' => $email, 'motDePasse' => $motDePasse));
}

// Met à jour le mot de passe d'un utilisateur.
function modifierMotDePasse(PDO $pdo, int $idUtilisateur, string $nouveauMotDePasse): bool
{
    $requete = $pdo->prepare('UPDATE users SET password = :motDePasse WHERE id_user = :idUtilisateur');
    return $requete->execute(array('motDePasse' => $nouveauMotDePasse, 'idUtilisateur' => $idUtilisateur));
}

// Liste tous les utilisateurs pour le panel administrateur.
function obtenirUtilisateurs(PDO $pdo): array
{
    $requete = $pdo->query('SELECT id_user, username, email, role, created_at FROM users ORDER BY created_at DESC');

    $utilisateurs = array();
    while ($ligne = $requete->fetch()) {
        $utilisateurs[] = $ligne;
    }
    return $utilisateurs;
}

// Transforme un compte utilisateur en administrateur ou inversement.
function modifierRole(PDO $pdo, int $idUtilisateur, string $role): bool
{
    if (!in_array($role, array('user', 'admin'), true)) {
        return false;
    }
    $requete = $pdo->prepare('UPDATE users SET role = :role WHERE id_user = :idUtilisateur');
    return $requete->execute(array('role' => $role, 'idUtilisateur' => $idUtilisateur));
}

// Supprime définitivement un compte utilisateur ou administrateur.
function supprimerUtilisateur(PDO $pdo, int $idUtilisateur): bool
{
    $requete = $pdo->prepare('DELETE FROM users WHERE id_user = :idUtilisateur');
    return $requete->execute(array('idUtilisateur' => $idUtilisateur));
}

// --- Authentification et inscription --------------------------------------------

// Vérifie un couple pseudo/mot de passe et renvoie l'utilisateur trouvé, ou false si invalide.
function verifierIdentifiants(PDO $pdo, string $pseudo, string $motDePasse)
{
    $utilisateur = trouverUtilisateur($pdo, $pseudo);
    if ($utilisateur && $utilisateur['password'] === $motDePasse) {
        return $utilisateur;
    }
    return false;
}

// Initialise les variables de session après une connexion réussie.
function demarrerSession(array $utilisateur): void
{
    $_SESSION['id_user'] = $utilisateur['id_user'];
    $_SESSION['username'] = $utilisateur['username'];
    $_SESSION['role'] = $utilisateur['role'];
}

// Valide un formulaire d'inscription et renvoie un message d'erreur, ou une chaîne vide si tout est valide.
function validerInscription(PDO $pdo, string $pseudo, string $email, string $motDePasse, string $confirmationMotDePasse): string
{
    if (strlen($pseudo) < 3) {
        return 'Le pseudo doit contenir au moins 3 caractères.';
    }
    if (strlen($motDePasse) < 8) {
        return 'Le mot de passe doit contenir au moins 8 caractères.';
    }
    if ($motDePasse !== $confirmationMotDePasse) {
        return 'Les deux mots de passe ne correspondent pas.';
    }
    if (utilisateurExiste($pdo, $pseudo, $email)) {
        return 'Ce pseudo ou cet email est déjà utilisé.';
    }
    return '';
}

// Valide un changement de mot de passe et renvoie un message d'erreur, ou une chaîne vide si tout est valide.
function validerChangementMotDePasse(string $motDePasseActuel, string $nouveauMotDePasse, string $confirmationNouveauMotDePasse, $utilisateur): string
{
    if (!$utilisateur || $utilisateur['password'] !== $motDePasseActuel) {
        return 'Mot de passe actuel incorrect.';
    }
    if (strlen($nouveauMotDePasse) < 8) {
        return 'Le nouveau mot de passe doit contenir au moins 8 caractères.';
    }
    if ($nouveauMotDePasse !== $confirmationNouveauMotDePasse) {
        return 'Les deux nouveaux mots de passe ne correspondent pas.';
    }
    return '';
}

// --- Administration --------------------------------------------------------------

// Applique une action admin (promouvoir / rétrograder / supprimer) sur un compte,
// avec protection anti-auto-modification. Renvoie le message à afficher.
function traiterActionUtilisateur(PDO $pdo, int $idUtilisateurConnecte, string $action, int $idCible): string
{
    if ($idCible === $idUtilisateurConnecte) {
        return 'Vous ne pouvez pas modifier votre propre compte depuis ce panneau.';
    }

    switch ($action) {
        case 'promouvoir':
            modifierRole($pdo, $idCible, 'admin');
            return 'Utilisateur promu administrateur.';
        case 'retrograder':
            modifierRole($pdo, $idCible, 'user');
            return 'Administrateur rétrogradé en utilisateur.';
        case 'supprimer':
            supprimerUtilisateur($pdo, $idCible);
            return 'Compte supprimé.';
        default:
            return '';
    }
}

// --- Contrôle d'accès --------------------------------------------------------------
// Ces fonctions doivent toujours être appelées avant tout affichage HTML (avant include('header.php')).

// Renvoie vers l'accueil si un visiteur déjà connecté tente d'accéder à login/register.
function redirigerSiConnecte(): void
{
    if (isset($_SESSION['id_user'])) {
        header('Location: index.php');
        exit();
    }
}

// Bloque l'accès à une page réservée aux utilisateurs connectés.
function exigerConnexion(): void
{
    if (!isset($_SESSION['id_user'])) {
        header('Location: login.php');
        exit();
    }
}

// Bloque l'accès à une page réservée aux administrateurs.
function exigerAdmin(): void
{
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php');
        exit();
    }
}
