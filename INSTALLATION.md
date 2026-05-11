# VUFYPMS — Complete Installation Guide
## Virtual University Final Year Project Management System

> This guide is written for beginners. Follow every step in order.
> Total setup time: approximately **15–20 minutes**.

---

## WHAT YOU WILL NEED

Before starting, you need to download and install **3 things**:

| # | Software | Purpose | Download Link |
|---|----------|---------|---------------|
| 1 | **XAMPP** | Runs PHP and MySQL on your computer | https://www.apachefriends.org/download.html |
| 2 | **Composer** | Installs PHP project dependencies | https://getcomposer.org/Composer-Setup.exe |
| 3 | **Git** | Downloads the project from GitHub | https://git-scm.com/download/win |

---

## PART 1 — INSTALL XAMPP

### Step 1.1 — Download XAMPP

1. Open your browser and go to:
   **https://www.apachefriends.org/download.html**

2. Click the download button for **PHP 8.2** (Windows version)
   - The file will be named something like `xampp-windows-x64-8.2.x-installer.exe`
   - File size is about **160 MB**

3. Wait for the download to finish

### Step 1.2 — Install XAMPP

1. Double-click the downloaded `.exe` file to run it
2. If Windows asks "Do you want to allow this app to make changes?" → click **Yes**
3. If a warning appears about User Account Control → click **OK**
4. Click **Next** on the welcome screen
5. Leave the default components selected → click **Next**
6. Installation folder: keep it as `C:\xampp` → click **Next**
7. Uncheck "Learn more about Bitnami" → click **Next**
8. Click **Next** to start installation
9. Wait for installation to complete (takes 2–3 minutes)
10. Click **Finish** — XAMPP Control Panel will open automatically

### Step 1.3 — Start XAMPP Services

When XAMPP Control Panel opens:

1. Click **Start** button next to **Apache**
   - Wait for it to turn **green** ✅
2. Click **Start** button next to **MySQL**
   - Wait for it to turn **green** ✅

> ⚠️ **Important:** Both Apache and MySQL must be GREEN before continuing.
> If they don't start, restart your computer and try again.

### Step 1.4 — Add PHP to System PATH

This step lets you run `php` commands from Command Prompt.

1. Press **Windows key** on your keyboard
2. Type: `environment variables` → click **"Edit the system environment variables"**
3. Click the **"Environment Variables"** button at the bottom
4. In the **"System variables"** section, find **"Path"** → double-click it
5. Click **"New"** button
6. Type exactly: `C:\xampp\php`
7. Click **OK** → **OK** → **OK** (close all windows)

**Verify it worked:**
1. Press **Windows + R** → type `cmd` → press Enter
2. In the black window, type: `php -v` and press Enter
3. You should see something like: `PHP 8.2.x ...`
   - If you see this ✅ — PHP is set up correctly
   - If you see an error ❌ — repeat Step 1.4

---

## PART 2 — INSTALL COMPOSER

### Step 2.1 — Download Composer

1. Open your browser and go to:
   **https://getcomposer.org/Composer-Setup.exe**
2. The file `Composer-Setup.exe` will download automatically

### Step 2.2 — Install Composer

1. Double-click `Composer-Setup.exe`
2. If Windows asks permission → click **Yes**
3. Click **Next** on the first screen
4. On the "Settings Check" screen:
   - It should automatically find PHP at `C:\xampp\php\php.exe`
   - If not, click **Browse** and navigate to `C:\xampp\php\php.exe`
5. Leave "Developer mode" unchecked → click **Next**
6. Skip the proxy settings → click **Next**
7. Click **Install**
8. Click **Finish**

**Verify Composer works:**
1. Close any open Command Prompt windows
2. Open a **new** Command Prompt (Windows + R → cmd → Enter)
3. Type: `composer --version` and press Enter
4. You should see: `Composer version 2.x.x`
   - If you see this ✅ — Composer is ready
   - If you see an error ❌ — restart your computer and try again

---

## PART 3 — INSTALL GIT

### Step 3.1 — Download Git

1. Open your browser and go to:
   **https://git-scm.com/download/win**
2. The download will start automatically
3. Run the downloaded installer
4. Click **Next** through all screens (default settings are fine)
5. Click **Install** → **Finish**

**Verify Git works:**
1. Open a new Command Prompt
2. Type: `git --version`
3. You should see: `git version 2.x.x` ✅

---

## PART 4 — CREATE THE DATABASE

### Step 4.1 — Open phpMyAdmin

1. Make sure XAMPP is running (Apache and MySQL are green)
2. Open your browser and go to:
   **http://localhost/phpmyadmin**
3. phpMyAdmin will open — it looks like a database management tool

### Step 4.2 — Create New Database

1. On the left side panel, click **"New"**
2. In the "Database name" box, type exactly: `vufypms`
3. From the dropdown next to it, select: `utf8mb4_unicode_ci`
4. Click the **"Create"** button
5. You will see `vufypms` appear in the left panel ✅

> You are done with phpMyAdmin. You can close this browser tab.

---

## PART 5 — DOWNLOAD THE PROJECT

### Step 5.1 — Open Command Prompt in the Right Location

1. Decide where you want to save the project, for example: `C:\Projects\`
2. Press **Windows + R** → type `cmd` → press Enter
3. Navigate to your chosen folder by typing:
   ```
   cd C:\Projects
   ```
   (If the folder doesn't exist, create it: `mkdir C:\Projects` then `cd C:\Projects`)

### Step 5.2 — Clone the Project from GitHub

1. In Command Prompt, type the following (replace `GITHUB_URL` with the actual GitHub link):
   ```
   git clone https://github.com/YOURNAME/vufypms.git
   ```
2. Press Enter — Git will download the project
3. Wait for it to finish (you will see files being downloaded)
4. When done, type:
   ```
   cd vufypms
   ```
5. You are now inside the project folder ✅

---

## PART 6 — SETUP THE PROJECT

Run each command one by one. **Wait for each to finish before typing the next.**

### Step 6.1 — Install PHP Packages

```
composer install
```
> This downloads all required packages. Takes 2–5 minutes. You will see many lines scrolling — that is normal.

### Step 6.2 — Create Environment File

```
copy .env.example .env
```
> This creates your configuration file.

### Step 6.3 — Generate Security Key

```
php artisan key:generate
```
> You should see: `Application key set successfully.` ✅

### Step 6.4 — Configure Database Connection

> ✅ **No editing needed for standard XAMPP setup!**
> The `.env.example` file already has the correct XAMPP default settings:
> - Database: `vufypms`
> - Username: `root`
> - Password: *(blank)*

**Only edit `.env` if you set a custom MySQL password in XAMPP:**
1. Open File Explorer → navigate to your project folder
2. Right-click on `.env` → **Open with** → **Notepad**
3. Find this line and add your password after the `=`:
   ```
   DB_PASSWORD=your_mysql_password_here
   ```
4. Press **Ctrl + S** to save → close Notepad

### Step 6.5 — Create Database Tables

```
php artisan migrate
```
> If it asks "Do you want to run this command? [yes/no]" → type `yes` and press Enter
> You should see many green lines saying "Migrated" ✅

### Step 6.6 — Add Demo Data (Users, Domains, Milestones)

```
php artisan db:seed
```
> This creates test accounts and sample data.
> You should see green lines saying "Seeding" ✅

### Step 6.7 — Create File Upload Folder Link

```
php artisan storage:link
```
> You should see: `The [public/storage] link has been connected to [storage/app/public].` ✅

---

## PART 7 — RUN THE PROJECT

### Step 7.1 — Start the Web Server

```
php artisan serve
```

You will see:
```
INFO  Server running on [http://127.0.0.1:8000]
```

### Step 7.2 — Open in Browser

1. Open your browser (Chrome, Firefox, Edge)
2. Go to: **http://localhost:8000**
3. You will see the VUFYPMS home page ✅

---

## PART 8 — LOGIN TO THE SYSTEM

Click **"Login"** on the home page and use these accounts:

### Admin Account (Full Control)
| Field | Value |
|-------|-------|
| Email | `admin@vu.edu.pk` |
| Password | `password` |

### Supervisor Account
| Field | Value |
|-------|-------|
| Email | `supervisor@vu.edu.pk` |
| Password | `password` |

### Student Account
| Field | Value |
|-------|-------|
| Email | `student@vu.edu.pk` |
| Password | `password` |

### All Test Accounts
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@vu.edu.pk | password |
| Supervisor 1 | supervisor@vu.edu.pk | password |
| Supervisor 2 | sara.khan@vu.edu.pk | password |
| Supervisor 3 | bilal.ahmed@vu.edu.pk | password |
| Student 1 | student@vu.edu.pk | password |
| Student 2 | ayesha@vu.edu.pk | password |
| Student 3 | umar@vu.edu.pk | password |
| Student 4 | fatima@vu.edu.pk | password |
| Student 5 | hamza@vu.edu.pk | password |
| Student 6 | zainab@vu.edu.pk | password |

---

## COMMON PROBLEMS & SOLUTIONS

### ❌ "php is not recognized"
**Cause:** PHP is not in the system PATH
**Fix:** Repeat Step 1.4 — add `C:\xampp\php` to PATH, then open a new Command Prompt

### ❌ "composer is not recognized"
**Cause:** Composer installation did not complete or PATH not updated
**Fix:** Restart your computer, then open a new Command Prompt and try again

### ❌ "No connection could be made" during migrate
**Cause:** MySQL is not running in XAMPP
**Fix:** Open XAMPP Control Panel → click **Start** next to **MySQL**

### ❌ "SQLSTATE: Unknown database 'vufypms'"
**Cause:** Database was not created
**Fix:** Open http://localhost/phpmyadmin → create database named `vufypms` (Step 4.2)

### ❌ "Class not found" error
**Cause:** Composer packages not installed
**Fix:** Run `composer install` again from inside the project folder

### ❌ Login says "These credentials do not match"
**Cause:** Seeder was not run
**Fix:** Run `php artisan db:seed` then try logging in again

### ❌ Port 3306 already in use (MySQL won't start)
**Cause:** Another program is using the MySQL port
**Fix:**
1. In XAMPP → click **Config** next to MySQL → click **my.ini**
2. Change `port=3306` to `port=3307`
3. In your `.env` file, change `DB_PORT=3306` to `DB_PORT=3307`
4. Restart MySQL in XAMPP

---

## EVERY TIME YOU WANT TO USE THE SYSTEM

1. Open **XAMPP Control Panel** → Start **Apache** and **MySQL**
2. Open Command Prompt → navigate to project folder:
   ```
   cd C:\Projects\vufypms
   ```
3. Start the server:
   ```
   php artisan serve
   ```
4. Open browser → go to **http://localhost:8000**

> You do NOT need to run migrate or seed again after the first setup.

---

## QUICK SUMMARY — ALL COMMANDS IN ORDER

```cmd
git clone https://github.com/YOURNAME/vufypms.git
cd vufypms
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve
```

Then open: **http://localhost:8000**

---

*Built for Virtual University of Pakistan — FYP Management System 2025–26*
