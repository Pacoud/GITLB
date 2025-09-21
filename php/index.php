<?php
/**
 * Page d'accueil - Équivalent PHP de la route /
 */

require_once 'database.php';

// Vérification si l'utilisateur est déjà connecté
if (isLoggedIn()) {
    redirect('connexion_reussie.php');
}

// Redirection vers la page de connexion
redirect('login.php');
?>
