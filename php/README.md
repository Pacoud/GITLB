# Application PHP de Connexion

Version PHP de l'application Flask de connexion utilisant la même base de données MySQL.

## Structure des fichiers

```
php/
├── config.php              # Configuration de la base de données
├── database.php            # Classe de gestion de la base de données
├── index.php               # Page d'accueil (redirige vers login)
├── login.php               # Page de connexion
├── connexion_reussie.php   # Page de connexion réussie
├── logout.php              # Page de déconnexion
├── init.php                # Script d'initialisation
├── css/
│   └── style.css           # Styles CSS
└── README.md               # Ce fichier
```

## Installation et utilisation

### 1. Prérequis
- Serveur web (Apache/Nginx) avec PHP 7.4+
- Extension PHP PDO MySQL
- Base de données MySQL (même que l'application Flask)

### 2. Configuration
La configuration de la base de données est dans `config.php` :
```php
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_NAME', '2025_M1');
define('DB_USER', 'root');
define('DB_PASS', 'rootpassword');
```

### 3. Initialisation
1. Placez le dossier `php/` dans votre serveur web
2. Accédez à `http://votre-serveur/php/init.php`
3. Suivez les instructions pour initialiser la base de données

### 4. Utilisation
1. Accédez à `http://votre-serveur/php/`
2. Utilisez les identifiants de test :
   - **Login :** test
   - **Password :** test

## Fonctionnalités

- ✅ Connexion à la même base de données MySQL que Flask
- ✅ Authentification utilisateur
- ✅ Gestion des sessions PHP
- ✅ Messages flash (succès, erreur, avertissement)
- ✅ Interface utilisateur responsive
- ✅ Mise à jour automatique de la date de connexion
- ✅ Déconnexion sécurisée

## Correspondance avec Flask

| Flask | PHP |
|-------|-----|
| `@app.route('/')` | `index.php` |
| `@app.route('/connexion')` | `login.php` |
| `@app.route('/connexion_reussie')` | `connexion_reussie.php` |
| `@app.route('/logout')` | `logout.php` |
| `session['user_id']` | `$_SESSION['user_id']` |
| `flash()` | `setFlashMessage()` / `getFlashMessage()` |
| `redirect()` | `redirect()` |

## Base de données

Utilise exactement la même structure de table que l'application Flask :
```sql
CREATE TABLE `user` (
    `user_id` INT NOT NULL AUTO_INCREMENT,
    `user_login` TEXT NOT NULL,
    `user_password` LONGTEXT NOT NULL,
    `user_compte_id` INT NOT NULL,
    `user_mail` TEXT NOT NULL,
    `user_date_new` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `user_date_login` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `cle-etrangere` (`user_compte_id`)
) ENGINE = InnoDB;
```

## Notes de sécurité

⚠️ **ATTENTION :** Cette version utilise des mots de passe en clair pour l'exemple. En production, utilisez des fonctions de hachage sécurisées comme `password_hash()` et `password_verify()`.

