<?php
/**
 * Page de connexion réussie - Équivalent PHP de la route /connexion_reussie
 */

require_once 'database.php';

// Vérification de la connexion
if (!isLoggedIn()) {
    setFlashMessage('Veuillez vous connecter.', 'warning');
    redirect('login.php');
}

// Récupération des messages flash
$flash = getFlashMessage();

// Récupération des informations de l'utilisateur depuis la session
$user_info = [
    'username' => $_SESSION['username'],
    'user_id' => $_SESSION['user_id'],
    'user_mail' => $_SESSION['user_mail'] ?? 'Non disponible'
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Réussie</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="success-container">
        <h2>🎉 Connexion Réussie !</h2>
        
        <!-- Affichage des messages flash -->
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>
        
        <div class="user-info">
            <h3>Informations de votre compte :</h3>
            <div class="info-item">
                <strong>Nom d'utilisateur :</strong> 
                <?php echo htmlspecialchars($user_info['username']); ?>
            </div>
            <div class="info-item">
                <strong>ID Utilisateur :</strong> 
                <?php echo htmlspecialchars($user_info['user_id']); ?>
            </div>
            <div class="info-item">
                <strong>Email :</strong> 
                <?php echo htmlspecialchars($user_info['user_mail']); ?>
            </div>
            <div class="info-item">
                <strong>Date de connexion :</strong> 
                <?php echo date('d/m/Y H:i:s'); ?>
            </div>
        </div>
        
        <div class="actions">
            <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
            <a href="login.php" class="btn btn-secondary">Retour à la connexion</a>
        </div>
    </div>
</body>
</html>
