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
- Interface admin pour approuver les utilisateurs

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

```bash
# Cloner le projet
git clone https://github.com/ton-projet/airsoft-api.git
cd airsoft-api

# Installer les dépendances
composer install

# Copier le fichier .env
cp .env .env.local
# Configurer ta base de données dans .env.local

# Créer la base et les tables
php bin/console doctrine:database:create
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

- [ ] Génération de token JWT ou formulaire de login
- [ ] Vérification isApproved dans le login
- [ ] CRUD complet pour Gear et Maintenance
- [ ] Dashboard admin simple pour validation des comptes

📚 Technologies
    Symfony 7
    Doctrine ORM
    MySQL ou PostgreSQL
    JWT (ou session)
    Postman/curl pour tester