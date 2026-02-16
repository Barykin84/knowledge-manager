#  MyGlossary - Gestionnaire de Connaissances Personnel

Une plateforme web complète permettant de créer, organiser et rechercher des articles et des définitions dans un espace sécurisé et privé.
- demo visible sur (https://pense.bacadem.org)

##  Fonctionnalités
- **Espace Personnel** : Chaque utilisateur dispose de son propre environnement de stockage.
- **Gestion de Contenu (CRUD)** : Création, modification et suppression d'articles avec titres et sous-titres.
- **Éditeur Riche** : Intégration de **TinyMCE** pour une mise en forme avancée du contenu.
- **Recherche Avancée** : Système de filtrage par mots-clés et navigation par index alphabétique.
- **Architecture PWA** : Compatible mobile et installable comme une application native.

##  Sécurité
- **Variables d'environnement** : Protection des clés API (TinyMCE) et des accès DB via un fichier `.env`.
- **Authentification** : Gestion sécurisée des sessions utilisateurs.
- **Protection des données** : Utilisation de PDO pour prévenir les injections SQL.

##  Stack Technique
- **Backend** : PHP 8.1+
- **Base de données** : MySQL
- **Frontend** : CSS3 (Responsive), JavaScript (Recherche dynamique)
- **Outils** : TinyMCE Cloud API, Google Chrome PWA standards

## 📥 Installation
1. Cloner le dépôt : `git clone https://github.com`
2. Configurer le fichier `.env` à la racine.
3. Importer le schéma SQL fourni dans votre base de données.