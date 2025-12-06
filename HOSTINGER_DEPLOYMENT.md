# 🚀 Hostinger Deployment Guide

## Deploy Laravel API ke Hostinger

Panduan lengkap deploy HyperHire Backend API ke Hostinger dengan Swagger documentation.

---

## 📋 Prerequisites

### Hostinger Requirements
- ✅ Hostinger Premium atau Business Hosting
- ✅ PHP 8.2+ support
- ✅ MySQL database
- ✅ SSH access (recommended)
- ✅ Custom domain: `hyperhire-api.id`

### Local Requirements
- ✅ Git
- ✅ FileZilla atau FTP client (jika tidak pakai SSH)

---

## 🎯 Hasil yang Diinginkan

Ketika user akses:
```
http://hyperhire-api.id/
```

Akan **otomatis redirect** ke:
```
http://hyperhire-api.id/api/documentation
```

✅ **Already configured!** Route redirect sudah dibuat di `routes/web.php`

---

## 📦 Step 1: Prepare Files for Upload

### 1.1 Optimize untuk Production

Di local, jalankan:

```bash
cd c:\laragon\www\be-hyperhire

# Update .env untuk production
# (See Step 2 below)

# Generate optimized autoloader
composer install --optimize-autoloader --no-dev

# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Generate Swagger docs
php artisan l5-swagger:generate
```

### 1.2 Create .env for Production

Buat file `.env.production` dengan content:

```env
APP_NAME="HyperHire API"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://hyperhire-api.id

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

# Database dari Hostinger
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456789_hyperhire
DB_USERNAME=u123456789_user
DB_PASSWORD=your_database_password

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

# Email configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=noreply@hyperhire-api.id
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@hyperhire-api.id"
MAIL_FROM_NAME="${APP_NAME}"
MAIL_ADMIN_EMAIL="admin@hyperhire-api.id"

# Swagger
L5_SWAGGER_GENERATE_ALWAYS=false
L5_SWAGGER_CONST_HOST=http://hyperhire-api.id
```

### 1.3 Files to Exclude (Jangan Upload)

**JANGAN upload folder ini:**
- `/node_modules`
- `/vendor` (akan di-generate di server)
- `.git`
- `.env` (buat baru di server)

**Yang perlu di-upload:**
- Semua folder `app/`, `config/`, `database/`, dll
- File `composer.json` dan `composer.lock`
- Folder `public/`
- Folder `resources/`
- Folder `routes/`
- Folder `storage/` (pastikan writable)
- Folder `bootstrap/`

---

## 🌐 Step 2: Setup Hostinger

### 2.1 Login ke Hostinger

1. Login ke [hpanel.hostinger.com](https://hpanel.hostinger.com)
2. Pilih hosting Anda
3. Pastikan domain `hyperhire-api.id` sudah pointing

### 2.2 Create Database

Di Hostinger Panel:

1. Go to **"Databases"** → **"MySQL Databases"**
2. Click **"Create new database"**
3. Database name: `u123456789_hyperhire` (prefix otomatis dari Hostinger)
4. Username: `u123456789_user` (auto-generated)
5. Password: Buat password yang kuat
6. **Save** credentials untuk `.env` nanti

### 2.3 Configure PHP Version

1. Go to **"Advanced"** → **"PHP Configuration"**
2. Select **PHP 8.2** atau yang lebih baru
3. Enable extensions:
   - ✅ mysqli
   - ✅ pdo_mysql
   - ✅ mbstring
   - ✅ xml
   - ✅ openssl
   - ✅ tokenizer
   - ✅ json
   - ✅ bcmath
   - ✅ ctype
   - ✅ fileinfo
4. Save

---

## 📤 Step 3: Upload Files

### Option A: Via FTP (FileZilla)

#### 3.1 Connect to FTP

```
Host: ftp.hyperhire-api.id
Username: u123456789 (dari Hostinger)
Password: your_ftp_password
Port: 21
```

#### 3.2 Upload Structure

Upload ke folder: `/domains/hyperhire-api.id/public_html/`

**PENTING**: Upload **SEMUA file Laravel** ke `public_html/`, bukan hanya folder `public/`!

```
/domains/hyperhire-api.id/public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── composer.json
├── composer.lock
├── artisan
└── ... (semua file Laravel)
```

### Option B: Via SSH (Recommended)

#### 3.1 Connect SSH

```bash
ssh u123456789@hyperhire-api.id
```

#### 3.2 Upload via Git

```bash
cd /home/u123456789/domains/hyperhire-api.id/public_html

# Clone repository
git clone https://github.com/your-repo/be-hyperhire.git .

# Atau upload zip dan extract
```

#### 3.3 Install Dependencies

```bash
# Install Composer jika belum ada
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Install Laravel dependencies
composer install --optimize-autoloader --no-dev
```

---

## ⚙️ Step 4: Configure Environment

### 4.1 Create .env File

Di server (via SSH atau File Manager):

```bash
cd /home/u123456789/domains/hyperhire-api.id/public_html
nano .env
```

Copy content dari `.env.production` yang sudah disiapkan.

**Update credentials:**
- `APP_KEY` - Generate baru dengan `php artisan key:generate`
- `DB_*` - Database credentials dari Hostinger
- `MAIL_*` - Email credentials

### 4.2 Generate Application Key

```bash
php artisan key:generate
```

### 4.3 Set Permissions

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R nobody:nobody storage
chown -R nobody:nobody bootstrap/cache
```

---

## 🗄️ Step 5: Setup Database

### 5.1 Run Migrations

Via SSH:
```bash
php artisan migrate --force
```

Atau via Hostinger cPanel:
- Go to **phpMyAdmin**
- Import manual dari local export

### 5.2 Seed Data (Optional)

```bash
php artisan db:seed --class=PeopleSeeder
```

---

## 🌍 Step 6: Configure Document Root

**PENTING!** Laravel document root harus ke `/public` folder.

### Via Hostinger hPanel

1. Go to **"Advanced"** → **"Website Settings"**
2. Find **"Document Root"**
3. Change from:
   ```
   /domains/hyperhire-api.id/public_html
   ```
   To:
   ```
   /domains/hyperhire-api.id/public_html/public
   ```
4. Save

### Via .htaccess (Alternative)

Jika tidak bisa ubah document root, buat `.htaccess` di `public_html/`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

## 🔧 Step 7: Optimize for Production

```bash
# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate Swagger docs
php artisan l5-swagger:generate
```

---

## ✅ Step 8: Test Deployment

### 8.1 Test Root URL

Buka browser:
```
http://hyperhire-api.id/
```

**Expected**: Otomatis redirect ke `/api/documentation`

### 8.2 Test Swagger UI

```
http://hyperhire-api.id/api/documentation
```

**Expected**: Swagger UI tampil dengan dokumentasi API

### 8.3 Test API Endpoint

```bash
curl http://hyperhire-api.id/api/people/recommended
```

**Expected**: JSON response dengan data people

---

## 🔄 Step 9: Setup Cronjob (Optional)

Via Hostinger cPanel:

1. Go to **"Advanced"** → **"Cron Jobs"**
2. Add new cron job:

**Command:**
```bash
cd /home/u123456789/domains/hyperhire-api.id/public_html && php artisan schedule:run
```

**Schedule:**
```
* * * * *
```

3. Save

---

## 📧 Step 10: Configure Email

### Using Hostinger Email

1. Create email: `noreply@hyperhire-api.id`
2. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=noreply@hyperhire-api.id
MAIL_PASSWORD=email_password
MAIL_ENCRYPTION=tls
MAIL_ADMIN_EMAIL=admin@hyperhire-api.id
```

### Test Email

```bash
php artisan check:person-likes-threshold
```

Check email inbox untuk verification.

---

## 🔒 Step 11: Security (Optional)

### Add Basic Auth for Swagger (Production)

Edit `config/l5-swagger.php`:

```php
'middleware' => [
    'api' => ['auth.basic'],
],
```

Or create IP whitelist middleware.

---

## 📊 Troubleshooting

### Issue 1: 500 Internal Server Error

**Solution:**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Check permissions
chmod -R 755 storage bootstrap/cache
```

### Issue 2: Swagger Not Loading

**Solution:**
```bash
# Regenerate Swagger
php artisan l5-swagger:generate

# Check storage path exists
ls -la storage/api-docs/

# Check route
php artisan route:list | grep documentation
```

### Issue 3: Database Connection Error

**Solution:**
- Verify DB credentials in `.env`
- Check Hostinger MySQL is running
- Test connection via phpMyAdmin

### Issue 4: Root Not Redirecting

**Solution:**
- Check document root pointing to `/public`
- Clear route cache: `php artisan route:clear`
- Check `.htaccess` in public folder exists

---

## 🎯 Final Verification

- [ ] `http://hyperhire-api.id/` redirects to Swagger
- [ ] Swagger UI loads successfully
- [ ] All 8 API endpoints visible
- [ ] "Try it out" feature works
- [ ] Database connection successful
- [ ] Sample data loaded (if seeded)
- [ ] Email configuration tested
- [ ] Cronjob scheduled (optional)

---

## 📝 Quick Commands Reference

```bash
# Clear all cache
php artisan optimize:clear

# Regenerate cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate Swagger
php artisan l5-swagger:generate

# Run migrations
php artisan migrate --force

# Check routes
php artisan route:list

# Test cronjob
php artisan check:person-likes-threshold
```

---

## 🔗 Access Points After Deployment

| URL | Description |
|-----|-------------|
| `http://hyperhire-api.id/` | Auto-redirect to Swagger |
| `http://hyperhire-api.id/api/documentation` | Swagger UI |
| `http://hyperhire-api.id/api/people` | API endpoints |
| `http://hyperhire-api.id/docs/api-docs.json` | OpenAPI JSON spec |

---

## 🎉 Success!

Setelah semua step selesai, Anda bisa akses:

**http://hyperhire-api.id/**

Dan otomatis akan redirect ke Swagger documentation! 🚀

---

**Date**: 2025-12-06  
**Deployment**: Hostinger  
**Domain**: hyperhire-api.id  
**Status**: ✅ Ready to Deploy
