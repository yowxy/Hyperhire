# 🆘 Troubleshooting: Site Can't Be Reached

## Masalah: "This site can't be reached"

Website `hyperhire-api.id` tidak bisa diakses sama sekali.

---

## ✅ Checklist Debugging

### 1. **Cek DNS Propagation**

**Test DNS:**
```bash
# Di terminal lokal
ping hyperhire-api.id
```

Atau cek online: https://dnschecker.org

**Jika DNS belum propagate:**
- Tunggu 24-48 jam
- Pastikan nameserver pointing ke Hostinger
- Cek di Hostinger panel: Domains → DNS Settings

---

### 2. **Cek Document Root di Hostinger**

**Via SSH:**
```bash
ssh u393576585@hyperhire-api.id

# Cek current directory
pwd
# Should show: /home/u393576585

# Go to domain folder
cd domains/hyperhire-api.id/public_html

# List files
ls -la
# Should see: app/, bootstrap/, config/, public/, etc
```

**Via hPanel:**
1. Login ke Hostinger hPanel
2. Advanced → Website Settings
3. **Document Root** harus diset ke:
   ```
   /domains/hyperhire-api.id/public_html/public
   ```
   **BUKAN**:
   ```
   /domains/hyperhire-api.id/public_html
   ```

---

### 3. **Cek File Structure**

Di SSH, jalankan:
```bash
cd /home/u393576585/domains/hyperhire-api.id/public_html

# Check if all folders exist
ls -l

# Should see:
# app/
# bootstrap/
# config/
# database/
# public/          ← PENTING!
# resources/
# routes/
# storage/
# vendor/
# .env
# artisan
```

**Jika `public/` folder tidak ada:**
- File belum terupload dengan benar
- Upload ulang semua file

---

### 4. **Cek Index File di Public**

```bash
ls -la public/

# Should see:
# index.php      ← PENTING!
# .htaccess      ← PENTING!
```

**Jika tidak ada:**
Upload folder `public/` dari local project.

---

### 5. **Cek .htaccess di public/**

```bash
cat public/.htaccess
```

**Should contain:**
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

**Jika tidak ada atau salah:**
Copy dari local project folder `public/.htaccess`

---

### 6. **Cek .env File**

```bash
cat .env | head -5

# Should show:
# APP_NAME="HyperHire API"
# APP_ENV=production
# APP_KEY=base64:...
# APP_DEBUG=false
# APP_URL=http://hyperhire-api.id
```

**Jika tidak ada:**
```bash
cp .env.example .env
nano .env
# Edit dengan credentials yang benar
```

**Generate App Key:**
```bash
php artisan key:generate
```

---

### 7. **Cek Composer Dependencies**

```bash
# Check if vendor exists
ls -la vendor/

# If not exists, install:
composer install --optimize-autoloader --no-dev
```

---

### 8. **Cek File Permissions**

```bash
# Set correct permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 public/index.php
chmod 644 public/.htaccess

# Set owner (replace nobody with your user if different)
chown -R u393576585:u393576585 storage
chown -R u393576585:u393576585 bootstrap/cache
```

---

### 9. **Test PHP**

Buat test file: `public/test.php`
```php
<?php
phpinfo();
```

Akses: `http://hyperhire-api.id/test.php`

**Jika muncul PHP info:**
- PHP bekerja
- Masalah di Laravel atau .htaccess

**Jika 404 atau error:**
- Document root salah
- Apache/Nginx config issue

---

### 10. **Cek Laravel Logs**

```bash
tail -f storage/logs/laravel.log
```

Lihat error messages.

---

## 🔧 Quick Fix Steps

### Step 1: Pastikan Document Root Benar

**Via Hostinger hPanel:**
1. Login hPanel
2. Advanced → Website Settings
3. Document Root: `/domains/hyperhire-api.id/public_html/public`
4. Save
5. **Restart Apache** (jika ada option)

### Step 2: Cek File Upload

**Via SSH:**
```bash
cd /home/u393576585/domains/hyperhire-api.id/public_html

# Should have these folders:
ls -d */ 
# app/ bootstrap/ config/ database/ public/ resources/ routes/ storage/ vendor/

# Check public folder
ls -la public/
# Should have: index.php and .htaccess
```

### Step 3: Install Dependencies

```bash
composer install --optimize-autoloader --no-dev
```

### Step 4: Set Environment

```bash
# Copy and edit .env
cp .env.example .env
nano .env

# Update:
# APP_URL=http://hyperhire-api.id
# DB_* credentials
# etc
```

### Step 5: Generate Key & Cache

```bash
php artisan key:generate
php artisan config:cache
php artisan route:cache
```

### Step 6: Test

```
http://hyperhire-api.id/test.php
```

---

## 🎯 Common Issues & Solutions

### Issue: DNS not resolved
**Solution:** 
- Wait for DNS propagation (24-48 hours)
- Or use IP address temporarily
- Check nameservers pointing to Hostinger

### Issue: 404 Error (not "can't be reached")
**Solution:**
- Document root harus ke `/public`
- Check .htaccess exists in public/

### Issue: 500 Internal Server Error
**Solution:**
```bash
tail -f storage/logs/laravel.log
chmod -R 755 storage
```

### Issue: Swagger error (Skipping unknown Likes)
**Solution:**
```bash
# Rename files (case-sensitive)
mv app/Models/likes.php app/Models/Likes.php
mv app/Models/person.php app/Models/Person.php

# Regenerate
php artisan l5-swagger:generate
```

---

## 📞 Next Steps

1. **Pastikan DNS sudah propagate**
   - Test: `ping hyperhire-api.id`
   
2. **Set Document Root** ke `/public_html/public`

3. **Upload semua file** dengan struktur yang benar

4. **Install composer dependencies**

5. **Setup .env**

6. **Test dengan file sederhana** (`test.php`)

7. **Jika berhasil**, lanjut ke Laravel config

---

## ✅ Verification Commands

Run di SSH:
```bash
# 1. Check directory
pwd

# 2. List files
ls -la

# 3. Check public folder
ls -la public/

# 4. Check vendor installed
ls -la vendor/

# 5. Check .env exists
cat .env | head -5

# 6. Test artisan
php artisan --version
```

**Expected**: Semua command return proper results

---

**Status**: Debugging in progress
**Date**: 2025-12-06
