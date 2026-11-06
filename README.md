# 🐳 Company Management — Stack Docker Microservices

Projet de système d'information développé autour de la gestion d'une entreprise.
Cette stack Docker orchestre les microservices Laravel situés dans le dossier configuré via `LARAVEL_DIR` dans `.env` (`../../laravel` par défaut) :

1. 🔐 **SSO & Gestion des Permissions (`authentication-api`)** : [`auth-user-manager`](../../laravel/auth-user-manager)
2. 👥 **Gestion des Ressources Humaines (`employe-api`)** : [`employe-api`](../../laravel/employe-api)
3. ✉️ **Messagerie & Synchronisation IMAP (`webmail-api`)** : [`webmail-api`](../../laravel/webmail-api)
4. 🗄️ **Base de Données MySQL 8.0 (`mysql`)** : bases `authentication_db`, `employee_db`, `webmail_db`
5. 🗃️ **Interface phpMyAdmin (`phpmyadmin`)**
6. 🌐 **Passerelle Proxy Nginx (`nginx`)**

---

## ⚙️ 1. Configuration (`.env`)

Les ports et les chemins vers les dépôts Laravel sont centralisés dans `.env` :

```dotenv
# Ports des services
MYSQL_PORT=3307
PHPMYADMIN_PORT=8080

AUTH_API_PORT=8081
EMPLOYEE_API_PORT=8082
WEBMAIL_API_PORT=8083

# Chemins vers les projets Laravel
LARAVEL_DIR=../../laravel
AUTH_API_PATH=${LARAVEL_DIR}/auth-user-manager
EMPLOYEE_API_PATH=${LARAVEL_DIR}/employe-api
WEBMAIL_API_PATH=${LARAVEL_DIR}/webmail-api
```

---

## 🚀 2. Démarrage de la Stack

```bash
docker compose up -d --build
```

Vérifier l'état des conteneurs :

```bash
docker compose ps
```

---

## 🗄️ 3. Initialisation des Bases de Données

```bash
# 1. Initialiser authentication-api (migrations, seeders par application + compte admin)
docker exec -it authentication-api sh -c "php artisan migrate:fresh --seed && php artisan app:create-admin admin 123456 --email=admin@admin.com"

# 2. Initialiser employe-api
docker exec -it employe-api php artisan migrate:fresh --seed

# 3. Initialiser webmail-api
docker exec -it webmail-api php artisan migrate:fresh --seed
```

---

## 🌐 4. Accès aux Services

| Service | URL d'Accès | Description |
| :--- | :--- | :--- |
| **Authentication API** | `http://localhost:8081` | SSO centralisé, utilisateurs, rôles et permissions |
| **Employe API** | `http://localhost:8082` | Gestion RH, contrats, congés, paie et carrière |
| **Webmail API** | `http://localhost:8083` | Messagerie multi-comptes (SSO + comptes directs `mail_accounts`) |
| **phpMyAdmin** | `http://localhost:8080` | Serveur : `mysql` — Utilisateur : `root` — Mot de passe : `root` |
