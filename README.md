# Airsoft Gear Tracker API

## 🎯 Objectif

Cette API Symfony permet aux joueurs d'airsoft de :

- Gérer la liste de leur matériel (répliques, équipements, accessoires, etc.)
- Enregistrer les maintenances ou réparations effectuées
- Accéder à l'application uniquement après validation manuelle par un administrateur

## 🚀 Fonctionnalités principales

- Création de compte utilisateur avec validation manuelle par un administrateur
- Authentification sécurisée via JWT
- Gestion des rôles : utilisateur et administrateur
- Ajout, édition et suppression du matériel personnel
- Création de fiches de maintenance associées à un matériel
- Consultation de l'historique des maintenances
- Attribution automatique d'un identifiant unique pour chaque matériel

## 🧱 Entités

- **User**
  - `id`, `email`, `password`, `roles`, `isApproved`, `isLocked`
- **Gear**
  - `id`, `name`, `type`, `brand`, `user_id`
- **Maintenance**
  - `id`, `date`, `description`, `gear_id`

## 🔐 Accès

L’accès à l’application est protégé par JWT. Un utilisateur ne peut se connecter qu’une fois son compte validé par un administrateur.

## 🛠️ Lancement rapide (dev)

```bash
composer install
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load
symfony server:start


## 🛠 Installation et configuration

dans le php.ini il faut activer l'extension openssl et pdo_sqlite

```bash
# Cloner le projet
git clone https://github.com/ton-projet/airsoft-api.git
cd airsoft-api

# Installer les dépendances
composer install

# Crée les clés JWT
mkdir -p config/jwt
openssl genrsa -out config/jwt/private.pem 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem


# Créer la base et les tables
# php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Démarrer le serveur
symfony server:start
```

## Requêtes API avec Curl

il est conseillé d'utiliser les scripts fournis dans le dossier `scripts` pour tester les requêtes API.

voir dans le dossier `scripts` pour les exemples de requêtes.

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
    JWT (LexikJWTAuthenticationBundle)
    Curl pour les requêtes