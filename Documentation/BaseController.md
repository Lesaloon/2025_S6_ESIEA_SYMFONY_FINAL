## 📄 Documentation – `BaseController.php`

Ce contrôleur expose **trois routes** pour tester les niveaux d’accès d’une API Symfony :

| Route     | Accès requis                  | Description                     |
| --------- | ----------------------------- | ------------------------------- |
| `/public` | Aucun                         | Route ouverte à tous            |
| `/user`   | Utilisateur authentifié       | Accessible avec un token valide |
| `/admin`  | Administrateur (`ROLE_ADMIN`) | Réservée aux comptes admin      |

---

## 🔓 Route publique – `/public`

### 📋 Description :

Accessible sans authentification. Idéal pour tester que le serveur répond.

### 📤 Requête :

```bash
curl -X GET http://localhost:8000/public
```

### 📥 Réponse :

```json
{
  "message": "Bienvenue sur la route publique !"
}
```

---

## 🔐 Route authentifiée – `/user`

### 📋 Description :

Accessible uniquement aux utilisateurs **connectés** et **approuvés** (`isApproved = true`).

### 📤 Requête (avec token JWT ou session cookie) :

Si tu utilises un **token JWT**, remplace `YOUR_TOKEN_HERE` :

```bash
curl -X GET http://localhost:8000/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 📥 Réponse (si connecté) :

```json
{
  "message": "Bienvenue utilisateur authentifié !",
  "email": "monemail@example.com"
}
```

---

## 🛡 Route admin – `/admin`

### 📋 Description :

Accessible uniquement aux utilisateurs ayant le rôle `ROLE_ADMIN`.

### 📤 Requête (avec token d’admin) :

```bash
curl -X GET http://localhost:8000/admin \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

### 📥 Réponse :

```json
{
  "message": "Bienvenue sur la route ADMIN !"
}
```

---

## ❌ Erreurs possibles

* `401 Unauthorized` → Si non connecté
* `403 Forbidden` → Si le rôle est insuffisant ou `isApproved = false`
* `200 OK` → Si l’utilisateur a le bon niveau d’accès

