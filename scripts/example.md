# 📘 Airsoft API – Endpoint Documentation with `curl` Examples

This document provides an overview of all available API endpoints exposed by the Airsoft system, including authentication, user management, gear management, and maintenance operations. Example `curl` commands are provided for each endpoint.

## 🌐 Base URL

```
http://localhost:8000
```

---

## 🔐 Authentication

### POST `/api/login`

Authenticate a user and receive a JWT token.

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@airsoft.local","password":"user123"}'
```

**Response:**

```json
{
  "token": "<JWT_TOKEN>"
}
```

---

## 👤 User Registration and Management

### POST `/api/register`

Register a new user account.

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"email":"user@airsoft.local","password":"user123"}'
```

### GET `/api/admin/unapproved-users`

List users who are pending approval (Admin only).

```bash
curl -X GET http://localhost:8000/api/admin/unapproved-users \
  -H "Authorization: Bearer <ADMIN_TOKEN>"
```

### POST `/api/admin/approve/{userId}`

Approve a pending user account (Admin only).

```bash
curl -X POST http://localhost:8000/api/admin/approve/123 \
  -H "Authorization: Bearer <ADMIN_TOKEN>"
```

### GET `/api/me`

Retrieve the profile of the currently authenticated user.

```bash
curl -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer <USER_TOKEN>"
```

---

## 🔒 Admin Routes

### GET `/admin`

Access protected admin-only route.

```bash
curl -X GET http://localhost:8000/admin \
  -H "Authorization: Bearer <ADMIN_TOKEN>"
```

---

## 🔓 Public Route

### GET `/public`

Public route accessible to anyone (may still require token).

```bash
curl -X GET http://localhost:8000/public \
  -H "Authorization: Bearer <USER_TOKEN>"
```

---

## 🧰 Gear Management

### GET `/api/gear`

List all gear associated with the current user.

```bash
curl -X GET http://localhost:8000/api/gear \
  -H "Authorization: Bearer <USER_TOKEN>"
```

### POST `/api/gear`

Create new gear.

```bash
curl -X POST http://localhost:8000/api/gear \
  -H "Authorization: Bearer <USER_TOKEN>" \
  -H "Content-Type: application/json" \
  -d '{"name":"M4A1","type":"Rifle","brand":"Tokyo Marui"}'
```

### GET `/api/gear/{id}`

Get detailed information about specific gear.

```bash
curl -X GET http://localhost:8000/api/gear/5 \
  -H "Authorization: Bearer <USER_TOKEN>"
```

### PUT `/api/gear/{id}`

Update gear details.

```bash
curl -X PUT http://localhost:8000/api/gear/5 \
  -H "Authorization: Bearer <USER_TOKEN>" \
  -H "Content-Type: application/json" \
  -d '{"name":"M4 CQB","type":"Rifle","brand":"G&G"}'
```

### DELETE `/api/gear/{id}`

Delete a piece of gear.

```bash
curl -X DELETE http://localhost:8000/api/gear/5 \
  -H "Authorization: Bearer <USER_TOKEN>"
```

---

## 🛠️ Maintenance Operations

### POST `/api/gear/{id}/maintenance`

Create a maintenance record for a specific gear item.

```bash
curl -X POST http://localhost:8000/api/gear/5/maintenance \
  -H "Authorization: Bearer <USER_TOKEN>" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Spring cleaning",
    "date": "2025-05-01",
    "description": "Disassembled and cleaned the internal components."
  }'
```

---

## ✅ Summary of All Endpoints

| Method | Endpoint                      | Description                        |
| ------ | ----------------------------- | ---------------------------------- |
| POST   | `/api/login`                  | Authenticate user                  |
| POST   | `/api/register`               | Register a new user                |
| GET    | `/api/admin/unapproved-users` | List unapproved users (admin only) |
| POST   | `/api/admin/approve/{userId}` | Approve a user (admin only)        |
| GET    | `/api/me`                     | View authenticated user profile    |
| GET    | `/admin`                      | Access admin route                 |
| GET    | `/public`                     | Public route                       |
| GET    | `/api/gear`                   | List user’s gear                   |
| POST   | `/api/gear`                   | Create gear                        |
| GET    | `/api/gear/{id}`              | View gear details                  |
| PUT    | `/api/gear/{id}`              | Edit gear                          |
| DELETE | `/api/gear/{id}`              | Delete gear                        |
| POST   | `/api/gear/{id}/maintenance`  | Add maintenance record to gear     |
