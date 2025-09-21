<?php
/**
 * Script d'initialisation de la base de données
 * Crée la table user et un utilisateur de test
 */

require_once 'database.php';

echo "<h2>Initialisation de la base de données PHP</h2>";

$db = new Database();

// Vérification de la connexion
echo "<p>1. Test de connexion à la base de données...</p>";
if ($db) {
    echo "<p style='color: green;'>✅ Connexion réussie</p>";
} else {
    echo "<p style='color: red;'>❌ Échec de la connexion</p>";
    exit;
}

// Vérification/création de la table
echo "<p>2. Vérification de la table 'user'...</p>";
if (!$db->checkUserTable()) {
    echo "<p>Table 'user' non trouvée. Création en cours...</p>";
    if ($db->createUserTable()) {
        echo "<p style='color: green;'>✅ Table 'user' créée avec succès</p>";
    } else {
        echo "<p style='color: red;'>❌ Erreur lors de la création de la table</p>";
        exit;
    }
} else {
    echo "<p style='color: green;'>✅ Table 'user' existe déjà</p>";
}

// Création d'un utilisateur de test
echo "<p>3. Création d'un utilisateur de test...</p>";
$testUser = $db->getUserByLogin('test');
if (!$testUser) {
    $userId = $db->createUser('test', 'test', 1, 'test@example.com');
    if ($userId) {
        echo "<p style='color: green;'>✅ Utilisateur de test créé avec l'ID: $userId</p>";
        echo "<p><strong>Identifiants de test :</strong></p>";
        echo "<ul>";
        echo "<li>Login: test</li>";
        echo "<li>Password: test</li>";
        echo "<li>Email: test@example.com</li>";
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>❌ Erreur lors de la création de l'utilisateur de test</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ L'utilisateur de test existe déjà</p>";
}

echo "<p>4. <a href='index.php'>Accéder à l'application</a></p>";

$db->close();
?>
