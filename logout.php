<?php
// On récupère param.php pour avoir la session_start()[cite: 7]
require_once 'parametrage/param.php';

// Détruire les variables de session
session_unset();
session_destroy();

// Rediriger vers l'accueil
header('Location: index.php');
exit();
?>