import mysql.connector
from mysql.connector import Error

def get_db_connection():
    """Établit une connexion à la base de données MySQL"""
    try:
        connection = mysql.connector.connect(
            host="localhost",
            port=3306,
            user="root",
            password="rootpassword",
            database="2025_M1"
        )
        return connection
    except Error as e:
        print(f"Erreur de connexion à la base de données: {e}")
        return None

def create_user_table():
    """Crée la table user selon la structure spécifiée"""
    connection = get_db_connection()
    if connection:
        try:
            cursor = connection.cursor()
            create_table_query = """
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
            """
            cursor.execute(create_table_query)
            connection.commit()
            print("Table 'user' créée avec succès")
        except Error as e:
            print(f"Erreur lors de la création de la table: {e}")
        finally:
            cursor.close()
            connection.close()

def get_user_by_login(login):
    """Récupère un utilisateur par son login"""
    connection = get_db_connection()
    if connection:
        try:
            cursor = connection.cursor(dictionary=True)
            query = "SELECT * FROM user WHERE user_login = %s"
            cursor.execute(query, (login,))
            user = cursor.fetchone()
            return user
        except Error as e:
            print(f"Erreur lors de la récupération de l'utilisateur: {e}")
            return None
        finally:
            cursor.close()
            connection.close()
    return None

def create_user(login, password, compte_id, email):
    """Crée un nouvel utilisateur"""
    connection = get_db_connection()
    if connection:
        try:
            cursor = connection.cursor()
            query = """
            INSERT INTO user (user_login, user_password, user_compte_id, user_mail) 
            VALUES (%s, %s, %s, %s)
            """
            cursor.execute(query, (login, password, compte_id, email))
            connection.commit()
            return cursor.lastrowid
        except Error as e:
            print(f"Erreur lors de la création de l'utilisateur: {e}")
            return None
        finally:
            cursor.close()
            connection.close()
    return None

def update_user_login_time(user_id):
    """Met à jour la date de dernière connexion"""
    connection = get_db_connection()
    if connection:
        try:
            cursor = connection.cursor()
            query = "UPDATE user SET user_date_login = CURRENT_TIMESTAMP WHERE user_id = %s"
            cursor.execute(query, (user_id,))
            connection.commit()
        except Error as e:
            print(f"Erreur lors de la mise à jour de la date de connexion: {e}")
        finally:
            cursor.close()
            connection.close()