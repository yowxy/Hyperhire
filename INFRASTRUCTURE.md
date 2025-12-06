#  Infrastructure Documentation

## Overview

HyperHire Backend API adalah RESTful API yang dibangun menggunakan **PHP Laravel 12** dengan **MySQL** sebagai database.

---

##  Technology Stack

| Component | Technology | Version | Purpose |
|-----------|-----------|---------|---------|
| **Backend Framework** | Laravel | 12.x | PHP Web Framework |
| **Language** | PHP | 8.2+ | Programming Language |
| **Database** | MySQL | 5.7+ | Relational Database |
| **API Documentation** | Swagger (L5-Swagger) | Latest | OpenAPI Documentation |
| **Queue** | Database Queue | - | Background Jobs (Email) |
| **Cache** | Database | - | Application Cache |
| **Session** | Database | - | Session Storage |

---

##  Database Architecture

### RDB Schema

**Database**: MySQL  
**Charset**: utf8mb4_unicode_ci  
**Collation**: utf8mb4_unicode_ci

#### Tables

1. **people** - Person profiles
   - Primary Key: `id`
   - Stores: name, age, location, pictures (JSON)
   
2. **likes** - Like/Dislike records
   - Primary Key: `id`
   - Foreign Key: `people_id` → `people.id` (ON DELETE CASCADE)
   - Type: ENUM('like', 'dislike')

**Full Schema**: See [DATABASE_SCHEMA.md](DATABASE_SCHEMA.md)

### Entity Relationship

```
┌──────────────┐         ┌──────────────┐
│   people     │ 1     N │    likes     │
│              ├─────────┤              │
│ id (PK)      │         │ id (PK)      │
│ name         │         │ people_id    │
│ age          │         │ type (ENUM)  │
│ location     │         └──────────────┘
│ pictures     │
└──────────────┘
```

### Migrations

Location: `database/migrations/`

1. `2025_12_06_124518_create_people_table.php`
2. `2025_12_06_124529_create_likes_table.php`

**Run Migrations:**
```bash
php artisan migrate
```

---

##  API Documentation (Swagger)

### Access Points

- **Local**: http://localhost/api/documentation
- **Production**: https://your-domain.com/api/documentation

### Swagger Configuration

**Config File**: `config/l5-swagger.php`

**Key Features:**
- OpenAPI 3.0 Specification
- Interactive API Testing
-  Request/Response Schemas
-  Auto-generated from annotations
-  JSON/YAML export support

### Generate Documentation
```bash
php artisan l5-swagger:generate
```

**Full Guide**: See [SWAGGER_GUIDE.md](SWAGGER_GUIDE.md)

---

##  API Endpoints

### Base URL
```
http://localhost/api
```

### Endpoints Summary

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/people` | Get all people (paginated) |
| GET | `/people/recommended` | Get recommended people |
| GET | `/people/{id}` | Get person by ID |
| POST | `/people` | Create new person |
| POST | `/likes/like` | Like a person |
| POST | `/likes/dislike` | Dislike a person |
| GET | `/likes/liked-people` | Get all liked people |
| GET | `/likes/disliked-people` | Get all disliked people |

**Full API Documentation**: http://localhost/api/documentation

---

##  Application Architecture

### Layer Structure

```
┌─────────────────────────────────────┐
│     HTTP Layer (Routes/API)         │
├─────────────────────────────────────┤
│     Controller Layer                │
│  - PersonController                 │
│  - LikesController                  │
├─────────────────────────────────────┤
│     Service Layer (Business Logic)  │
│  - PersonService                    │
│  - LikesService                     │
├─────────────────────────────────────┤
│     Model Layer (ORM)               │
│  - Person                           │
│  - Likes                            │
├─────────────────────────────────────┤
│     Database Layer (MySQL)          │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│     Event System (Async)            │
│  - PersonLikesThresholdReached      │
│  - SendPersonLikesThresholdEmail    │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│     Console/Cronjob                 │
│  - CheckPersonLikesThreshold        │
└─────────────────────────────────────┘
```

### Design Patterns

1. **Repository Pattern** (via Services)
2. **Event-Driven Architecture** (Email notifications)
3. **Dependency Injection** (Controller constructors)
4. **DTO Pattern** (Request validation)
5. **Observer Pattern** (Eloquent Events)

---

##  Email Infrastructure

### Email System Architecture

```
┌──────────────────────────────────────┐
│  Trigger: Person reaches 50+ likes   │
└───────────────┬──────────────────────┘
                ↓
┌───────────────▼──────────────────────┐
│  Event: PersonLikesThresholdReached  │
└───────────────┬──────────────────────┘
                ↓
┌───────────────▼──────────────────────┐
│  Listener: SendPersonLikesEmail      │
│  (Queued Job)                        │
└───────────────┬──────────────────────┘
                ↓
┌───────────────▼──────────────────────┐
│  Mail: PersonLikesThresholdMail      │
│  Template: emails/person-likes-...   │
└───────────────┬──────────────────────┘
                ↓
┌───────────────▼──────────────────────┐
│  Send to: MAIL_ADMIN_EMAIL           │
└──────────────────────────────────────┘
```

### Email Configuration

**Development (Log):**
```env
MAIL_MAILER=log
```
Emails saved to `storage/logs/laravel.log`

**Production (SMTP):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_ADMIN_EMAIL=admin@yourdomain.com
```

### Queue System

**Driver**: Database

**Start Queue Worker:**
```bash
php artisan queue:work
```

**Monitor Queue:**
```bash
php artisan queue:listen --verbose
```

---

##  Cronjob System

### Schedule Configuration

**File**: `routes/console.php`

**Schedule:**
```php
Schedule::command('check:person-likes-threshold')
    ->daily()
    ->at('00:00')
    ->timezone('Asia/Jakarta')
    ->emailOutputOnFailure(config('mail.admin_email'));
```

### Cronjob Setup

#### Development (Manual)
```bash
php artisan check:person-likes-threshold
```

#### Production (Cron)
Add to server crontab:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

**Verify Schedule:**
```bash
php artisan schedule:list
```

---

##  Security Features

### 1. Input Validation
- All endpoints have request validation
- Type checking (string, integer, array)
- Range validation (age: 18-120, per_page: 1-100)
- URL validation for pictures

### 2. Database Security
- Foreign key constraints
- Cascade delete for data integrity
- ENUM for type safety
- SQL injection prevention (Eloquent ORM)

### 3. Error Handling
- Try-catch blocks in all controllers
- Proper HTTP status codes
- Consistent error response format
- No sensitive data in responses

### 4. CORS (if needed)
Can be added via middleware or Apache/Nginx config

---

##  File Structure

```
c:\laragon\www\be-hyperhire\
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── CheckPersonLikesThreshold.php
│   ├── Events/
│   │   └── PersonLikesThresholdReached.php
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Controller.php (Swagger base schemas)
│   │       └── API/
│   │           ├── PersonController.php
│   │           └── LikesController.php
│   ├── Listeners/
│   │   └── SendPersonLikesThresholdEmail.php
│   ├── Mail/
│   │   └── PersonLikesThresholdMail.php
│   ├── Models/
│   │   ├── Person.php
│   │   └── Likes.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   └── EventServiceProvider.php
│   └── Services/
│       ├── PersonService.php
│       └── LikesService.php
├── config/
│   ├── l5-swagger.php (Swagger config)
│   └── mail.php (Email config)
├── database/
│   ├── migrations/
│   │   ├── 2025_12_06_124518_create_people_table.php
│   │   └── 2025_12_06_124529_create_likes_table.php
│   └── seeders/
│       └── PeopleSeeder.php
├── resources/
│   └── views/
│       └── emails/
│           └── person-likes-threshold.blade.php
├── routes/
│   ├── api.php (API routes)
│   └── console.php (Cronjob schedule)
└── storage/
    └── api-docs/ (Generated Swagger docs)
```

---

##  Deployment Guide

### Prerequisites

1. **Server Requirements:**
   - PHP 8.2+
   - MySQL 5.7+
   - Composer
   - Web Server (Apache/Nginx)

2. **PHP Extensions:**
   - OpenSSL
   - PDO
   - Mbstring
   - Tokenizer
   - XML
   - Ctype
   - JSON

### Deployment Steps

#### 1. Clone & Setup
```bash
cd /var/www/
git clone <repository-url> be-hyperhire
cd be-hyperhire
composer install --optimize-autoloader --no-dev
```

#### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=be_hyperhire
DB_USERNAME=db_user
DB_PASSWORD=secure_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ADMIN_EMAIL=admin@yourdomain.com

L5_SWAGGER_GENERATE_ALWAYS=false
L5_SWAGGER_CONST_HOST=https://api.yourdomain.com
```

#### 3. Database Setup
```bash
php artisan migrate --force
php artisan db:seed --class=PeopleSeeder  # Optional
```

#### 4. Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan l5-swagger:generate
```

#### 5. Set Permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 6. Setup Queue Worker
Create systemd service: `/etc/systemd/system/laravel-queue.service`
```ini
[Unit]
Description=Laravel Queue Worker

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/be-hyperhire/artisan queue:work --sleep=3 --tries=3

[Install]
WantedBy=multi-user.target
```

Start service:
```bash
sudo systemctl enable laravel-queue
sudo systemctl start laravel-queue
```

#### 7. Setup Cronjob
```bash
crontab -e
```

Add:
```
* * * * * cd /var/www/be-hyperhire && php artisan schedule:run >> /dev/null 2>&1
```

#### 8. Web Server Configuration

**Apache Virtual Host:**
```apache
<VirtualHost *:80>
    ServerName api.yourdomain.com
    DocumentRoot /var/www/be-hyperhire/public

    <Directory /var/www/be-hyperhire/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/api-error.log
    CustomLog ${APACHE_LOG_DIR}/api-access.log combined
</VirtualHost>
```

**Nginx:**
```nginx
server {
    listen 80;
    server_name api.yourdomain.com;
    root /var/www/be-hyperhire/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### 9. SSL Certificate (Let's Encrypt)
```bash
sudo certbot --apache -d api.yourdomain.com
# or
sudo certbot --nginx -d api.yourdomain.com
```

---

## 🧪 Testing Infrastructure

### Test Swagger
```
https://api.yourdomain.com/api/documentation
```

### Test API
```bash
curl https://api.yourdomain.com/api/people/recommended
```

### Test Email
```bash
php artisan check:person-likes-threshold
```

Check logs:
```bash
tail -f storage/logs/laravel.log
```
