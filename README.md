<p align="center">
  <img src="https://nostalgic-css.github.io/NES.css/favicon.png" alt="NES.css" width="80">
</p>

<h1 align="center">🎮 NES Blog — Mini Blog Symfony</h1>

<p align="center">
  <strong>Un blog rétro pixel art développé avec Symfony & NES.css</strong><br>
  Projet réalisé dans le cadre du module PHP Symfony — IPSSI Paris 2026
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Symfony-7.x-black?style=flat-square&logo=symfony" alt="Symfony">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/NES.css-Retro%20UI-e76e55?style=flat-square" alt="NES.css">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License">
</p>

---

## 📖 Présentation

**NES Blog** est une application web de type blog développée avec le framework **Symfony** (dernière version) et stylisée avec le thème rétro **[NES.css](https://github.com/nostalgic-css/NES.css)**, inspiré de l'univers Nintendo 8-bit.

Le projet implémente un système complet de gestion de contenu avec trois niveaux d'accès (visiteur, utilisateur connecté, administrateur), une gestion des articles par catégories, un système de commentaires avec modération, et une interface responsive au look pixel art unique.

---

## ✨ Fonctionnalités

### 👤 Visiteur (non connecté)
- Consultation de la page d'accueil avec les derniers articles
- Navigation dans la liste des articles avec filtre par catégorie
- Lecture des articles et de leurs commentaires approuvés
- Inscription et connexion

### 🎮 Utilisateur connecté (`ROLE_USER`)
- Toutes les fonctionnalités du visiteur
- Ajout de commentaires sur les articles (soumis à validation)
- Consultation et modification de son profil personnel

### 👑 Administrateur (`ROLE_ADMIN`)
- Toutes les fonctionnalités de l'utilisateur connecté
- **Dashboard admin** avec statistiques globales (articles, utilisateurs, commentaires, catégories)
- **Gestion des articles** : création, modification, suppression (CRUD complet)
- **Gestion des catégories** : création, modification, suppression
- **Gestion des utilisateurs** : consultation, activation/désactivation de comptes, promotion/rétrogradation admin
- **Modération des commentaires** : approbation, rejet, suppression

---

## 🛠️ Technologies utilisées

| Technologie | Utilisation |
|---|---|
| **Symfony 7.x** | Framework PHP principal (MVC) |
| **PHP 8.2+** | Langage backend |
| **Doctrine ORM** | Gestion de la base de données et des entités |
| **Twig** | Moteur de templates |
| **Symfony Security** | Authentification, rôles et autorisations |
| **NES.css** | Framework CSS rétro pixel art |
| **Press Start 2P** | Police pixel art (Google Fonts) |
| **MySQL 8.0** | Base de données relationnelle |
| **Git & GitHub** | Gestion de version et hébergement du code |

---

## 📐 Architecture du projet

### Entités et relations

```
┌──────────┐       OneToMany       ┌──────────┐       ManyToOne       ┌──────────────┐
│   User   │ ───────────────────── │   Post   │ ───────────────────── │   Category   │
│          │                       │          │                       │              │
│ id       │                       │ id       │                       │ id           │
│ email    │                       │ title    │                       │ name         │
│ password │                       │ content  │                       │ description  │
│ roles    │                       │ picture  │                       └──────────────┘
│ firstName│                       │ publishedAt                      
│ lastName │                       │ author ──┘                       
│ isActive │                       └──────────┘                       
└──────────┘                            │                             
      │                                 │ OneToMany                   
      │           OneToMany             │                             
      │                            ┌──────────┐                       
      └─────────────────────────── │ Comment  │                       
                                   │          │                       
                                   │ id       │                       
                                   │ content  │                       
                                   │ createdAt│                       
                                   │ status   │                       
                                   └──────────┘                       
```

**Relations :**
- **User → Post** : OneToMany — Un utilisateur peut créer plusieurs articles
- **User → Comment** : OneToMany — Un utilisateur peut écrire plusieurs commentaires
- **Post → Comment** : OneToMany — Un article peut avoir plusieurs commentaires
- **Post → Category** : ManyToOne — Chaque article appartient à une seule catégorie

### Structure des fichiers

```
mini-blog/
├── config/
│   └── packages/
│       └── security.yaml          # Configuration authentification & rôles
├── src/
│   ├── Controller/
│   │   ├── HomeController.php     # Page d'accueil
│   │   ├── PostController.php     # CRUD articles + commentaires
│   │   ├── AdminController.php    # Dashboard & gestion admin
│   │   ├── SecurityController.php # Connexion / Déconnexion
│   │   ├── RegistrationController.php  # Inscription
│   │   └── ProfileController.php  # Profil utilisateur
│   ├── Entity/
│   │   ├── User.php               # Entité utilisateur
│   │   ├── Post.php               # Entité article
│   │   ├── Comment.php            # Entité commentaire
│   │   └── Category.php           # Entité catégorie
│   ├── Form/
│   │   ├── PostType.php           # Formulaire article
│   │   ├── CommentType.php        # Formulaire commentaire
│   │   ├── CategoryType.php       # Formulaire catégorie
│   │   ├── RegistrationFormType.php  # Formulaire inscription
│   │   └── ProfileType.php        # Formulaire profil
│   ├── Repository/
│   │   ├── UserRepository.php
│   │   ├── PostRepository.php
│   │   ├── CommentRepository.php
│   │   └── CategoryRepository.php
│   └── DataFixtures/
│       └── AppFixtures.php        # Données initiales (admin, catégories, articles)
└── templates/
    ├── base.html.twig             # Layout principal (navbar, footer, NES.css)
    ├── home/index.html.twig       # Page d'accueil
    ├── post/                      # Templates articles (index, show, new, edit)
    ├── admin/                     # Templates admin (dashboard, users, posts, etc.)
    ├── security/login.html.twig   # Page de connexion
    ├── registration/register.html.twig  # Page d'inscription
    └── profile/                   # Templates profil (show, edit)
```

---

## 🚀 Installation

### Prérequis

- PHP 8.2 ou supérieur
- Composer
- MySQL 8.0 (ou MariaDB)
- Symfony CLI (optionnel mais recommandé)
- Git

### Étapes

```bash
# 1. Cloner le dépôt
git clone https://github.com/votre-username/mini-blog-symfony.git
cd mini-blog-symfony

# 2. Installer les dépendances PHP
composer install

# 3. Configurer la base de données
#    Modifier le fichier .env avec vos identifiants MySQL :
#    DATABASE_URL="mysql://root:@127.0.0.1:3306/mini_blog?serverVersion=8.0"

# 4. Créer la base de données
php bin/console doctrine:database:create

# 5. Exécuter les migrations
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# 6. Charger les données de test (fixtures)
php bin/console doctrine:fixtures:load

# 7. Lancer le serveur
symfony server:start
# ou
php -S 127.0.0.1:8000 -t public
```

L'application sera accessible sur **http://127.0.0.1:8000**

---

## 🔐 Comptes de test

| Rôle | Email | Mot de passe |
|---|---|---|
| 👑 Administrateur | `admin@blog.com` | `admin123` |
| 👤 Utilisateur | `user@blog.com` | `user123` |

---

## 🔒 Sécurité & Rôles

### Système d'authentification

Le projet utilise le **Security Bundle** de Symfony avec :
- Hachage automatique des mots de passe (bcrypt/argon2)
- Protection CSRF sur tous les formulaires
- Hiérarchie des rôles : `ROLE_ADMIN` hérite de `ROLE_USER`

### Contrôle d'accès

| Route | Accès requis |
|---|---|
| `/` | Public |
| `/post/` | Public |
| `/post/{id}` | Public (commentaires réservés aux connectés) |
| `/register` | Public |
| `/login` | Public |
| `/profile` | `ROLE_USER` |
| `/post/new` | `ROLE_ADMIN` |
| `/post/{id}/edit` | `ROLE_ADMIN` |
| `/admin/*` | `ROLE_ADMIN` |

### Modération des commentaires

Les commentaires soumis par les utilisateurs sont en statut **"pending"** par défaut. Seul l'administrateur peut les approuver ou les rejeter depuis le dashboard admin. Seuls les commentaires approuvés sont visibles publiquement.

---

## 🎨 Design & Interface

Le projet utilise le framework CSS **[NES.css](https://github.com/nostalgic-css/NES.css)** qui apporte une esthétique rétro pixel art inspirée des consoles Nintendo 8-bit.

**Caractéristiques de l'interface :**
- 🌙 Thème sombre personnalisé
- 🎮 Composants NES.css (boutons, containers, icônes pixel art)
- ✏️ Police "Press Start 2P" (Google Fonts)
- 📱 Design responsive (mobile, tablette, desktop)
- ⚡ Système de grille CSS Grid pour l'affichage des articles
- 💬 Messages flash stylisés (succès, erreur)

---

## 📝 Commandes utiles

```bash
# Lancer le serveur de développement
symfony server:start

# Créer une migration après modification d'entité
php bin/console make:migration

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Recharger les fixtures (reset la BDD)
php bin/console doctrine:fixtures:load

# Vider le cache
php bin/console cache:clear

# Voir toutes les routes
php bin/console debug:router
```

---

## 📂 Commits Git recommandés

Le projet a été développé avec des commits fréquents et descriptifs :

```
feat: initial project setup with Symfony skeleton
feat: create User, Post, Comment, Category entities
feat: configure security with role-based authentication
feat: implement registration and login forms
feat: add post CRUD for admin
feat: add comment system with moderation
feat: implement admin dashboard with user management
feat: add category management
feat: implement user profile (view & edit)
style: integrate NES.css retro theme
feat: add data fixtures (admin, users, articles, categories)
docs: add README with installation guide
```

---



