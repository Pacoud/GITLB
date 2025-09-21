<?php
/**
 * Configuration de la base de données MySQL
 * Utilise la même base de données que l'application Flask
 */

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_NAME', '2025_M1');
define('DB_USER', 'root');
define('DB_PASS', 'rootpassword');

// Configuration de session
define('SESSION_LIFETIME', 300); // 5 minutes en secondes

// Démarrer la session
session_start();

// Configuration du fuseau horaire
date_default_timezone_set('Europe/Paris');
?>
