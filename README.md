# Airsoft Gear Tracker API

## 🎯 Objectif

Cette API Symfony permet aux joueurs d'airsoft de :
- Gérer la liste de leur matériel (répliques, équipements, etc.)
- Enregistrer les maintenances ou réparations effectuées
- Accéder à l'application seulement après validation manuelle par un administrateur

## 🚀 Fonctionnalités principales

- Création de compte utilisateur (avec validation admin requise)
- Authentification sécurisée
- Rôles utilisateur / admin
- Ajout et gestion du matériel par utilisateur
- Ajout de fiches de maintenance liées au matériel
- Consultation de l'historique de maintenance
- Assignation automatique de code d'identification unique pour chaque matériel

## 🧱 Entités

- **User**
  - `id`, `email`, `password`, `roles`, `isApproved`
- **Gear**
  - `id`, `name`, `type`, `brand`, `user_id`
- **Maintenance**
  - `id`, `date`, `description`, `gear_id`

## 🔐 Sécurité

- Authentification par formulaire ou JWT (au choix)
- Contrôle d’accès par rôle (`ROLE_USER`, `ROLE_ADMIN`)
- Utilisateurs non approuvés ne peuvent pas se connecter

## 🛠 Installation et configuration

dans le php.ini il faut activer l'extension openssl et pdo_sqlite

```bash
# Cloner le projet
git clone https://github.com/ton-projet/airsoft-api.git
cd airsoft-api

# Installer les dépendances
composer install

# Créer la base et les tables
# php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Démarrer le serveur
symfony server:start
```
🧪 Exemples de requêtes (via curl)
📌 Enregistrement utilisateur

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"motdepasse"}'
```
📌 Connexion
```bash
curl -X POST http://localhost:8000/api/login_check \
  -H "Content-Type: application/json" \
  -d '{"username":"test@example.com","password":"motdepasse"}'
```
📌 Ajout de matériel (après login)
```bash
curl -X POST http://localhost:8000/api/gears \
  -H "Authorization: Bearer VOTRE_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"M4", "type":"AEG", "brand":"G&G"}'
```
📂 Organisation du code

    /src/Entity → Entités Doctrine

    /src/Controller → Contrôleurs d'API

    /src/Security → Configuration des accès

    /config → Routage, sécurité, etc.

📌 À faire

- [X] Génération de token JWT ou formulaire de login
- [X] Vérification isApproved dans le login
- [ ] CRUD complet pour Gear et Maintenance

📚 Technologies
    Symfony 7
    Doctrine ORM
    SQLite
    JWT
    Curl pour les requêtes