from flask import Flask, render_template, request, redirect, url_for, session, flash
from datetime import timedelta
from gestion_bdd import get_user_by_login, update_user_login_time, create_user_table
from models import User

app = Flask(__name__)
app.secret_key = "cle_flask"  
app.permanent_session_lifetime = timedelta(minutes=5) # Session de 5 min

# Page d'accueil avec lien vers le login
@app.route('/')
def home():
    return render_template('connexion.html')

# Page de login - Gère l'affichage (GET) et la soumission du formulaire (POST)
@app.route('/connexion', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        # 1. Récupérer les données du formulaire
        username = request.form['username']
        password = request.form['password'] 
        
        # 2. VÉRIFICATION DANS LA BASE DE DONNÉES
        user_data = get_user_by_login(username)
        if user_data and user_data['user_password'] == password:  
            # Mise à jour de la date de dernière connexion
            update_user_login_time(user_data['user_id'])
            
            session.permanent = True
            session['user_id'] = user_data['user_id']
            session['username'] = user_data['user_login']
            session['user_mail'] = user_data['user_mail']
            flash('Connexion réussie!', 'success')
            return redirect(url_for('connexion_reussie'))
        else:
            flash('Identifiant ou mot de passe incorrect.', 'danger')
    
    # Affiche la page de login si GET ou si échec de connexion
    return render_template('connexion.html')

# Page de profil accessible seulement si connecté
@app.route('/connexion_reussie')
def connexion_reussie():
    if 'user_id' not in session:
        flash('Veuillez vous connecter.', 'warning')
        return redirect(url_for('connexion'))
    
    # Récupérer les informations de l'utilisateur depuis la session
    user_info = {
        'username': session['username'],
        'user_id': session['user_id'],
        'user_mail': session.get('user_mail', 'Non disponible')
    }
    
    return render_template('connexion_reussie.html', user=user_info)

# Route pour la déconnexion
@app.route('/logout')
def logout():
    session.clear()
    flash('Vous avez été déconnecté.', 'info')
    return redirect(url_for('login'))

# Route pour initialiser la base de données 
@app.route('/init_db')
def init_database():
    try:
        create_user_table()
        flash('Base de données initialisée avec succès!', 'success')
    except Exception as e:
        flash(f'Erreur lors de l\'initialisation: {str(e)}', 'danger')
    return redirect(url_for('connexion'))

if __name__ == '__main__':
    # Initialiser la base de données au démarrage
    create_user_table()
    app.run(debug=True)