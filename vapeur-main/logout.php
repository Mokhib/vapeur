<?php
// Déconnexion : on récupère param.php uniquement pour la session_start() déjà active.
require_once 'parametrage/param.php';

// Détruire les variables de session
session_unset();
session_destroy();

// Rediriger vers l'accueil
header('Location: index.php');
exit();
