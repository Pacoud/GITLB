<?php
/**
 * Page de déconnexion - Équivalent PHP de la route /logout
 */

require_once 'database.php';

// Destruction de la session
session_destroy();

// Redirection vers la page de connexion avec un message
setFlashMessage('Vous avez été déconnecté.', 'info');
redirect('login.php');
?>
