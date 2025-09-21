<?php
/**
 * Page de connexion - Équivalent PHP de la route /connexion
 */

require_once 'database.php';

$db = new Database();

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        setFlashMessage('Veuillez remplir tous les champs.', 'danger');
    } else {
        // Récupération de l'utilisateur depuis la base de données
        $user = $db->getUserByLogin($username);
        
        if ($user && $user['user_password'] === $password) {
            // Mise à jour de la date de dernière connexion
            $db->updateUserLoginTime($user['user_id']);
            
            // Création de la session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['user_login'];
            $_SESSION['user_mail'] = $user['user_mail'];
            
            setFlashMessage('Connexion réussie!', 'success');
            redirect('connexion_reussie.php');
        } else {
            setFlashMessage('Identifiant ou mot de passe incorrect.', 'danger');
        }
    }
}

// Récupération des messages flash
$flash = getFlashMessage();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Connexion</h2>
        
        <!-- Affichage des messages flash -->
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="input-group">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            <div class="input-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
        
        <div class="links">
            <a href="index.php">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>
