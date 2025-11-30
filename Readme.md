🚀 Hostinger + GitHub Auto Deployment (Laravel)

This guide explains how to deploy a Laravel project to Hostinger using Git + Auto Deployment, including private GitHub repos, symlink setup, environment configuration, and database connection.

# 📌 Part 1 — Prepare Your Project

## 1. Build Frontend Assets

```bash
npm install
```

```bash
npm run build
```

## 2. Ensure Build Output Is Committed

### In .gitignore, remove or comment out:

```bash
/public/build
```

## 3. Push the Project to GitHub
### Create a private repository and push your project.

# 📌 Part 2 — Configure Hostinger Git Deployment

### 4. Create a Site in Hostinger

* Create a domain or sub-domain
* Set PHP version: Advanced → PHP Configuration

### 5. Add Hostinger SSH Key to GitHub
* Hostinger → Advanced → Git
* Copy the SSH Key
* GitHub → Repo → Settings → Deploy Keys → Add key
* (Optional) Enable Allow write access

### 6. Add Your GitHub Repo to Hostinger
* Hostinger → Advanced → Git
* Add Repository
* Repository type: SSH URL
* Branch: master or main
* Directory: (leave blank)
* Click Create

# 📌 Part 3 — Fix the Document Root (Symlink Setup)
Hostinger defaults to public_html, but Laravel needs repo_name/public.

## 7. SSH Into Hostinger

```bash
ssh username@yourHostingerIP
```

## 8. Rename Default public_html

```bash
mv public_html public_html_old
```

## 9. Create Symlink to Laravel Public Folder

```bash
ln -s public_html_old/repo_name/public public_html
```
Replace repo_name with the folder Hostinger created for your repo.

# 📌 Part 4 — Configure Laravel

## 10. Create the .env File
```bash
cd public_html_old/repo_name
cp .env.example .env
```
## 11. Generate App Key
```bash
php artisan key:generate
```
## 12. Install Composer Dependencies
```bash
composer install
```

# 📌 Part 5 — Database Setup
## 13. Create a Database
* Hostinger:
* Databases → Management
* Create new DB + user + password
## 14. Update .env File
In Hostinger File Manager:
```bash
Files → public_html_old → repo_name → .env
```
Update:
```bash
DB_DATABASE=yourdbname
DB_USERNAME=yourdbuser
DB_PASSWORD=yourdbpassword
```
Run migrations if necessary:
```bash
php artisan migrate
```
# 📌 Part 6 — Enable Auto Deployment

## 15. Get Hostinger Webhook URL
* Hostinger → Advanced → Git
* Find your repo under Managed Repositories
* Enable Auto Deployment
* Copy the generated Webhook URL

## 16. Add Webhook to GitHub
* GitHub → Repo → Settings → Webhooks → Add Webhook
* Payload URL:  Paste Hostinger webhook URL
* Content Type: application/json
* Secret:       (leave empty)
* Events:       Select Just the push event.

🎉 Deployment Complete
Now every time you push: GitHub → triggers Hostinger → Hostinger auto-pulls → deploys instantly.