<?php
/**
 * Fonctions de gestion de la base de données
 * Équivalent PHP des fonctions de gestion_bdd.py
 */

require_once 'config.php';

class Database {
    private $connection;
    
    public function __construct() {
        $this->connect();
    }
    
    /**
     * Établit une connexion à la base de données MySQL
     */
    private function connect() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }
    
    /**
     * Récupère un utilisateur par son login
     */
    public function getUserByLogin($login) {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM user WHERE user_login = ?");
            $stmt->execute([$login]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Crée un nouvel utilisateur
     */
    public function createUser($login, $password, $compte_id, $email) {
        try {
            $stmt = $this->connection->prepare("
                INSERT INTO user (user_login, user_password, user_compte_id, user_mail) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$login, $password, $compte_id, $email]);
            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Met à jour la date de dernière connexion
     */
    public function updateUserLoginTime($user_id) {
        try {
            $stmt = $this->connection->prepare("
                UPDATE user 
                SET user_date_login = CURRENT_TIMESTAMP 
                WHERE user_id = ?
            ");
            $stmt->execute([$user_id]);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de la date de connexion: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Vérifie si la table user existe
     */
    public function checkUserTable() {
        try {
            $stmt = $this->connection->prepare("SHOW TABLES LIKE 'user'");
            $stmt->execute();
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification de la table: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Crée la table user si elle n'existe pas
     */
    public function createUserTable() {
        try {
            $createTableQuery = "
                CREATE TABLE IF NOT EXISTS `user` (
                    `user_id` INT NOT NULL AUTO_INCREMENT,
                    `user_login` TEXT NOT NULL,
                    `user_password` LONGTEXT NOT NULL,
                    `user_compte_id` INT NOT NULL,
                    `user_mail` TEXT NOT NULL,
                    `user_date_new` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `user_date_login` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`user_id`),
                    UNIQUE KEY `cle-etrangere` (`user_compte_id`)
                ) ENGINE = InnoDB
            ";
            $this->connection->exec($createTableQuery);
            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de la table: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Ferme la connexion
     */
    public function close() {
        $this->connection = null;
    }
}

// Fonctions utilitaires pour les messages flash
function setFlashMessage($message, $type = 'info') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Fonction pour rediriger
function redirect($url) {
    header("Location: $url");
    exit();
}
?>
