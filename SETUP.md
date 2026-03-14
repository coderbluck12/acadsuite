## Prerequisites
- **PHP 8.2+** ✅ (confirmed: PHP 8.4.7)
- **Composer** — Install from https://getcomposer.org/Composer-Setup.exe (Windows installer, ~5 min)
- **MySQL** — Running locally (XAMPP / Laragon / MySQL Workbench)

---

## ⚡ localhost Quick-Start (No Hosts File Needed!)

```powershell
cd "c:\Users\Oyenola Philip\Downloads\acadsuite"
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Then open in browser:
| Page | URL |
|---|---|
| 🌐 Landing | http://localhost:8000 |
| 🏢 Pentagonware HQ | http://localhost:8000/superadmin/login |
| 🎓 Demo Tenant Portal | http://localhost:8000/?tenant=demo |
| 👤 Admin panel | http://localhost:8000/admin/profile?tenant=demo |
| 👨‍🎓 Student login | http://localhost:8000/login?tenant=demo |

**Credentials (from seeder):**
- Pentagonware: `admin@pentagonware.com` / `password`
- Demo admin: `admin@demo.local` / `password`
- Demo student: `mariam@demo.local` / `password`

---


1. Download: https://getcomposer.org/Composer-Setup.exe
2. Run the installer (it will auto-detect your PHP)
3. Restart your terminal/PowerShell
4. Verify: `composer --version`

---

## Step 2: Install Laravel & Dependencies

```powershell
cd "c:\Users\Oyenola Philip\Downloads\acadsuite"
composer install
```

> If you get a "no composer.lock" error, run `composer update` instead.

---

## Step 3: Generate App Key

```powershell
php artisan key:generate
```

---

## Step 4: Create the Database

Open MySQL and run:
```sql
CREATE DATABASE acadsuite CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Then update your `.env` file password if needed:
```
DB_PASSWORD=your_mysql_password
```

---

## Step 5: Run Migrations & Seed

```powershell
php artisan migrate --seed
```

This creates all 11 tables and seeds:
- 1 Pentagonware super admin (`admin@pentagonware.com` / `password`)
- 1 Demo tenant (`demo.acadsuite.local`)
- 1 Demo admin user (`admin@demo.local` / `password`)
- 2 Demo students

---

## Step 6: Create Storage Symlink

```powershell
php artisan storage:link
```

---

## Step 7: Add Your Hosts File Entries

Open Notepad **as Administrator**, edit:
`C:\Windows\System32\drivers\etc\hosts`

Add these lines at the bottom:
```
127.0.0.1  acadsuite.local
127.0.0.1  admin.acadsuite.local
127.0.0.1  demo.acadsuite.local
```

---

## Step 8: Start the Server

```powershell
php artisan serve --host=acadsuite.local --port=8000
```

---

## Access Your Three Tiers

| Tier | URL | Credentials |
|---|---|---|
| 🌐 Landing Page | http://acadsuite.local:8000 | — |
| 🏢 Pentagonware HQ | http://admin.acadsuite.local:8000 | `admin@pentagonware.com` / `password` |
| 🎓 Demo Tenant Portal | http://demo.acadsuite.local:8000 | `admin@demo.local` / `password` |
| 👨‍🎓 Demo Student | http://demo.acadsuite.local:8000/login | `mariam@demo.local` / `password` |

---

## Copy Static Assets

Copy the files from `static/assests/` to `public/assets/`:

```powershell
Copy-Item "static\assests\*" -Destination "public\assets\" -Recurse -Force
```

---

## Register a New Academic (Test the full flow)
1. Go to http://acadsuite.local:8000
2. Click "Create Your Suite"
3. Fill the form and choose a subdomain (e.g., `drjane`)
4. Add `127.0.0.1 drjane.acadsuite.local` to hosts file
5. Visit http://drjane.acadsuite.local:8000

---

## Commands Reference

```powershell
# Fresh start (wipe DB and re-seed)
php artisan migrate:fresh --seed

# Clear all caches
php artisan cache:clear && php artisan config:clear && php artisan view:clear

# View all routes
php artisan route:list
```
