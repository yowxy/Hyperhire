# ✅ Pre-Deployment Checklist

Checklist sebelum upload ke Hostinger.

## 📦 Preparation

### 1. Optimize Code
```bash
# Update dependencies
composer install --optimize-autoloader --no-dev

# Generate Swagger docs
php artisan l5-swagger:generate

# Test locally
php artisan serve
# Visit: http://localhost (should redirect to /api/documentation)
```

### 2. Files to Upload
- ✅ All `app/` folder
- ✅ All `bootstrap/` folder
- ✅ All `config/` folder
- ✅ All `database/` folder
- ✅ All `public/` folder
- ✅ All `resources/` folder
- ✅ All `routes/` folder
- ✅ All `storage/` folder (empty, tapi struktur folder)
- ✅ `composer.json` dan `composer.lock`
- ✅ `artisan` file

### 3. Files to EXCLUDE
- ❌ `/node_modules`
- ❌ `/vendor` (akan di-install di server)
- ❌ `.env` (buat baru di server)
- ❌ `.git`
- ❌ `/storage/logs/*.log`
- ❌ `/storage/framework/cache/*`
- ❌ `/storage/framework/sessions/*`
- ❌ `/storage/framework/views/*`

---

## 🌐 Hostinger Setup

### 1. Domain Configuration
- [ ] Domain `hyperhire-api.id` sudah pointing ke Hostinger
- [ ] DNS propagation selesai (check: https://dnschecker.org)

### 2. Database
- [ ] MySQL database created
- [ ] Database name noted: `u123456789_hyperhire`
- [ ] Database user noted: `u123456789_user`
- [ ] Database password saved

### 3. Email
- [ ] Email `noreply@hyperhire-api.id` created
- [ ] SMTP credentials saved
- [ ] Admin email `admin@hyperhire-api.id` ready

### 4. PHP Configuration
- [ ] PHP version set to 8.2+
- [ ] Required extensions enabled:
  - [ ] mysqli
  - [ ] pdo_mysql
  - [ ] mbstring
  - [ ] openssl
  - [ ] xml
  - [ ] tokenizer
  - [ ] json

---

## 📤 Upload Process

### Via FTP
```
Host: ftp.hyperhire-api.id
Username: u123456789
Password: [your_ftp_password]
Upload to: /domains/hyperhire-api.id/public_html/
```

### Via SSH (Recommended)
```bash
ssh u123456789@hyperhire-api.id
cd /domains/hyperhire-api.id/public_html
# Upload files
```

---

## ⚙️ Server Configuration

### 1. Create .env
```bash
cd /home/u123456789/domains/hyperhire-api.id/public_html
nano .env
```

Copy from `.env.example` dan update:
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=http://hyperhire-api.id`
- [ ] Database credentials
- [ ] Email credentials
- [ ] `MAIL_ADMIN_EMAIL=admin@hyperhire-api.id`
- [ ] `L5_SWAGGER_CONST_HOST=http://hyperhire-api.id`

### 2. Generate App Key
```bash
php artisan key:generate
```

### 3. Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### 4. Set Permissions
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 5. Document Root
**PENTING!** Set document root ke:
```
/domains/hyperhire-api.id/public_html/public
```

Di Hostinger hPanel:
- Advanced → Website Settings → Document Root

### 6. Run Migrations
```bash
php artisan migrate --force
```

### 7. Seed Data (Optional)
```bash
php artisan db:seed --class=PeopleSeeder
```

### 8. Cache Config
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 9. Generate Swagger
```bash
php artisan l5-swagger:generate
```

---

## ✅ Testing

### 1. Root Redirect
```
http://hyperhire-api.id/
```
**Expected**: Redirect to `/api/documentation`

### 2. Swagger UI
```
http://hyperhire-api.id/api/documentation
```
**Expected**: Swagger UI loads with API docs

### 3. API Test
```bash
curl http://hyperhire-api.id/api/people/recommended
```
**Expected**: JSON response with people data

### 4. Database
- [ ] Tables `people` dan `likes` exist
- [ ] Sample data loaded (jika di-seed)

### 5. Email (Optional)
```bash
php artisan check:person-likes-threshold
```
**Expected**: Email sent to admin

---

## 🔄 Cronjob Setup (Optional)

Hostinger cPanel → Advanced → Cron Jobs

**Command**:
```
cd /home/u123456789/domains/hyperhire-api.id/public_html && php artisan schedule:run
```

**Schedule**: `* * * * *` (setiap menit)

---

## 🐛 Troubleshooting

### Issue: 500 Error
```bash
# Check logs
tail -f storage/logs/laravel.log

# Clear cache
php artisan optimize:clear
```

### Issue: Swagger Not Loading
```bash
php artisan l5-swagger:generate
php artisan config:clear
```

### Issue: Root Not Redirecting
- Check document root pointing to `/public`
- Check `routes/web.php` has redirect
- Clear route cache: `php artisan route:clear`

---

## 📋 Final Verification

- [ ] Root URL redirects to Swagger
- [ ] Swagger UI loads completely
- [ ] All 8 endpoints visible in Swagger
- [ ] "Try it out" works for test endpoint
- [ ] Database connected successfully
- [ ] No errors in logs
- [ ] .env configured correctly
- [ ] File permissions set
- [ ] Composer dependencies installed

---

## 🎉 Done!

Jika semua ✅, deployment selesai!

**Access**: http://hyperhire-api.id/

---

**Date**: 2025-12-06  
**Target**: Hostinger  
**Domain**: hyperhire-api.id
