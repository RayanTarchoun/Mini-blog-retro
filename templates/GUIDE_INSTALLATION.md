# 🎮 Mini Blog Symfony - NES.css Theme
## Guide d'installation complet (PowerShell)

---

## 1. Prérequis

Assure-toi d'avoir installé :
- PHP 8.2+ (`php -v`)
- Composer (`composer -V`)
- Symfony CLI (`symfony -v`) — optionnel mais recommandé
- MySQL/MariaDB ou PostgreSQL
- Node.js + npm (pour les assets si besoin)
- Git (`git --version`)

---

## 2. Création du projet Symfony

```powershell
# Se placer dans le dossier de travail
cd C:\Users\Rayan\Projects

# Créer le projet Symfony (dernière version)
composer create-project symfony/skeleton mini-blog

# Entrer dans le projet
cd mini-blog

# Installer le pack webapp (inclut Twig, Doctrine, Security, Form, etc.)
composer require webapp

# Installer les dépendances supplémentaires
composer require symfony/security-bundle
composer require symfony/form
composer require symfony/validator
composer require doctrine/doctrine-fixtures-bundle --dev
composer require symfony/maker-bundle --dev
```

---

## 3. Configuration de la base de données

Modifier le fichier `.env` à la racine du projet :

```
DATABASE_URL="mysql://root:@127.0.0.1:3306/mini_blog?serverVersion=8.0"
```

Puis créer la base :

```powershell
php bin/console doctrine:database:create
```

---

## 4. Création des entités

```powershell
# Les entités sont déjà fournies dans le dossier src/Entity/
# Copie les fichiers fournis, puis lance la migration :

php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

---

## 5. Création du système d'authentification

```powershell
# Générer le système de sécurité
php bin/console make:security:form-login
```

Quand il te demande :
- Security controller class: `SecurityController`
- Login template: `security/login.html.twig`

---

## 6. Lancer le serveur

```powershell
# Avec Symfony CLI
symfony server:start

# OU avec PHP built-in
php -S 127.0.0.1:8000 -t public
```

---

## 7. Créer un admin via les fixtures

```powershell
php bin/console doctrine:fixtures:load
```

---

## 8. Git

```powershell
git init
git add .
git commit -m "Initial commit - Mini Blog Symfony NES.css"
git branch -M main
git remote add origin https://github.com/ton-username/mini-blog-symfony.git
git push -u origin main
```

---

## Structure du projet (fichiers à créer/modifier)

```
mini-blog/
├── src/
│   ├── Controller/
│   │   ├── HomeController.php
│   │   ├── PostController.php
│   │   ├── AdminController.php
│   │   ├── SecurityController.php
│   │   ├── RegistrationController.php
│   │   └── ProfileController.php
│   ├── Entity/
│   │   ├── User.php
│   │   ├── Post.php
│   │   ├── Comment.php
│   │   └── Category.php
│   ├── Form/
│   │   ├── PostType.php
│   │   ├── CommentType.php
│   │   ├── CategoryType.php
│   │   ├── RegistrationFormType.php
│   │   └── ProfileType.php
│   ├── Repository/
│   │   ├── UserRepository.php
│   │   ├── PostRepository.php
│   │   ├── CommentRepository.php
│   │   └── CategoryRepository.php
│   └── DataFixtures/
│       └── AppFixtures.php
├── templates/
│   ├── base.html.twig
│   ├── home/
│   │   └── index.html.twig
│   ├── post/
│   │   ├── index.html.twig
│   │   ├── show.html.twig
│   │   ├── new.html.twig
│   │   └── edit.html.twig
│   ├── admin/
│   │   ├── dashboard.html.twig
│   │   ├── users.html.twig
│   │   ├── posts.html.twig
│   │   ├── categories.html.twig
│   │   ├── category_form.html.twig
│   │   └── comments.html.twig
│   ├── security/
│   │   └── login.html.twig
│   ├── registration/
│   │   └── register.html.twig
│   └── profile/
│       ├── show.html.twig
│       └── edit.html.twig
└── config/
    └── packages/
        └── security.yaml
```
