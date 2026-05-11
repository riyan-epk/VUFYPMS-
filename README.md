# VUFYPMS — Virtual University Final Year Project Management System

A complete web-based FYP management portal built with **Laravel 10**, **MySQL**, and **Bootstrap 5**.

---

## 🖥️ PLATFORM REQUIREMENTS

### What You Need to Install

| Software | Version | Download Link |
|----------|---------|---------------|
| **XAMPP** | 8.1+ (PHP 8.1) | https://www.apachefriends.org/ |
| **Composer** | 2.x | https://getcomposer.org/download/ |
| **Git** | Latest | https://git-scm.com/ |
| **Node.js** | 18+ (optional) | https://nodejs.org/ |

> ⚠️ XAMPP must include **PHP 8.1+** and **MySQL 5.7+**. Download XAMPP 8.1.x or later.

---

## 🚀 QUICK SETUP (Automated)

Open **PowerShell as Administrator** in this folder and run:

```powershell
.\setup.ps1
```

---

## 📋 MANUAL SETUP (Step by Step)

### Step 1 — Start XAMPP
1. Open **XAMPP Control Panel**
2. Start **Apache** and **MySQL**

### Step 2 — Create Database
1. Open browser → `http://localhost/phpmyadmin`
2. Click **New** → Database name: `vufypms` → **Create**

### Step 3 — Install Dependencies
Open Command Prompt / PowerShell in the `vufypms/` folder:
```bash
composer install
```

### Step 4 — Configure Environment
```bash
copy .env.example .env
php artisan key:generate
```

Edit `.env` file with your database credentials:
```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vufypms
DB_USERNAME=root
DB_PASSWORD=
```

### Step 5 — Run Migrations & Seed Data
```bash
php artisan migrate --seed
```

### Step 6 — Create Storage Link
```bash
php artisan storage:link
```

### Step 7 — Start Development Server
```bash
php artisan serve
```

Open browser → **http://localhost:8000**

---

## 🔑 DEFAULT LOGIN CREDENTIALS

| Role | Email | Password | Access |
|------|-------|---------|--------|
| **Admin** | admin@vu.edu.pk | password | Full system control |
| **Supervisor** | supervisor@vu.edu.pk | password | Supervisor portal |
| **Student 1** | student@vu.edu.pk | password | Student portal |
| **Student 2** | student2@vu.edu.pk | password | Student portal |

---

## 📁 PROJECT STRUCTURE

```
vufypms/
├── app/
│   ├── Http/Controllers/     ← Application controllers
│   │   ├── Auth/             ← Login, Register
│   │   ├── Public/           ← Guest-facing pages
│   │   ├── Student/          ← Student module
│   │   ├── Supervisor/       ← Supervisor module
│   │   └── Admin/            ← Admin module
│   ├── Models/               ← Eloquent models (16)
│   └── Http/Middleware/      ← RoleMiddleware, CheckActive
├── database/
│   ├── migrations/           ← 16 database tables
│   └── seeders/              ← Demo data seeders
├── resources/views/
│   ├── layouts/              ← Master layouts
│   ├── auth/                 ← Login/Register
│   ├── public/               ← Guest pages
│   ├── student/              ← Student views
│   ├── supervisor/           ← Supervisor views
│   └── admin/                ← Admin views
├── routes/web.php            ← All application routes
├── storage/app/public/       ← Uploaded files
├── .env                      ← Environment config
└── composer.json             ← PHP dependencies
```

---

## 🎯 MODULES & FEATURES

### 👨‍🎓 Student Portal
- ✅ Personalized dashboard with stats
- ✅ Team creation and member invitation
- ✅ Project proposal submission and tracking
- ✅ Document upload (SRS, Design, Reports)
- ✅ Milestone progress tracking
- ✅ Messaging with supervisor
- ✅ View evaluation marks and feedback
- ✅ Presentation schedule viewer

### 👨‍🏫 Supervisor Portal
- ✅ Supervisor dashboard with workload summary
- ✅ Proposal review (approve/revise/reject)
- ✅ Progress monitoring per team
- ✅ Meeting slot scheduling
- ✅ Evaluation mark entry
- ✅ Feedback and revision management

### 🔧 Admin Panel
- ✅ Complete user management (CRUD + activate/deactivate)
- ✅ Project domain management
- ✅ Semester and deadline configuration
- ✅ Milestone definition per semester
- ✅ Supervisor-team assignment
- ✅ Announcement management
- ✅ Analytics dashboard (Chart.js)
- ✅ Project archive management

### 🌐 Public Portal
- ✅ System information home page
- ✅ Public announcements listing
- ✅ Completed project search
- ✅ FYP guidelines

---

## 🛠️ TECH STACK

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 10.x (PHP 8.1+) |
| Database | MySQL 8.0 |
| ORM | Laravel Eloquent |
| Frontend | Blade + Bootstrap 5.3 |
| Icons | Bootstrap Icons 1.11 |
| JS | jQuery 3.7 + DataTables + Chart.js |
| Alerts | SweetAlert2 |
| Auth | Laravel Session Auth |
| Web Server | Apache (XAMPP) |

---

## 🔧 COMMON COMMANDS

```bash
# Run dev server
php artisan serve

# Fresh migration with seed
php artisan migrate:fresh --seed

# Clear all caches
php artisan optimize:clear

# Create storage symlink
php artisan storage:link

# View all routes
php artisan route:list
```

---

## 📞 SUPPORT

Built for Virtual University of Pakistan — Final Year Project 2025-26.

For setup issues, ensure:
- PHP version ≥ 8.1 (`php -v` to check)
- MySQL is running in XAMPP
- `vufypms` database exists
- `.env` file has correct DB credentials
