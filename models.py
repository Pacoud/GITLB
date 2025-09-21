from datetime import datetime
from typing import Optional

class User:
    """Modèle représentant un utilisateur dans la base de données"""
    
    def __init__(self, user_id: int = None, user_login: str = "", user_password: str = "", 
                 user_compte_id: int = 0, user_mail: str = "", 
                 user_date_new: datetime = None, user_date_login: datetime = None):
        self.user_id = user_id
        self.user_login = user_login
        self.user_password = user_password
        self.user_compte_id = user_compte_id
        self.user_mail = user_mail
        self.user_date_new = user_date_new or datetime.now()
        self.user_date_login = user_date_login or datetime.now()
    
    def __repr__(self):
        return f"User(id={self.user_id}, login='{self.user_login}', email='{self.user_mail}')"
    
    def to_dict(self):
        """Convertit l'objet User en dictionnaire"""
        return {
            'user_id': self.user_id,
            'user_login': self.user_login,
            'user_password': self.user_password,
            'user_compte_id': self.user_compte_id,
            'user_mail': self.user_mail,
            'user_date_new': self.user_date_new,
            'user_date_login': self.user_date_login
        }
    
    @classmethod
    def from_dict(cls, data: dict):
        """Crée un objet User à partir d'un dictionnaire"""
        return cls(
            user_id=data.get('user_id'),
            user_login=data.get('user_login', ''),
            user_password=data.get('user_password', ''),
            user_compte_id=data.get('user_compte_id', 0),
            user_mail=data.get('user_mail', ''),
            user_date_new=data.get('user_date_new'),
            user_date_login=data.get('user_date_login')
        )
    
    def verify_password(self, password: str) -> bool:
        """Vérifie si le mot de passe fourni correspond au mot de passe de l'utilisateur"""
        # ATTENTION: Dans un vrai projet, utilisez hashlib pour hacher les mots de passe
        # Ici on fait une comparaison simple pour l'exemple
        return self.user_password == password