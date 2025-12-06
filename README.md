# HyperHire Backend API

Backend API untuk dating application dengan fitur like/dislike dan automated email notifications.

## 🚀 Quick Start

### Local Development

```bash
# 1. Install dependencies
composer install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Configure database in .env
DB_DATABASE=be_hyperhire
DB_USERNAME=root
DB_PASSWORD=

# 4. Add admin email
MAIL_ADMIN_EMAIL=admin@hyperhire.com

# 5. Run migrations
php artisan migrate

# 6. (Optional) Seed sample data
php artisan db:seed --class=PeopleSeeder

# 7. Generate Swagger docs
php artisan l5-swagger:generate

# 8. Start server
php artisan serve
```

**Access Points:**
- API: http://localhost/api
- Swagger: http://localhost/api/documentation

---

## 🌐 Hostinger Deployment

Untuk deploy ke Hostinger dengan domain `hyperhire-api.id`:

**Baca panduan lengkap**: [HOSTINGER_DEPLOYMENT.md](HOSTINGER_DEPLOYMENT.md)

**Hasil:**
- `http://hyperhire-api.id/` → Auto-redirect ke Swagger documentation
- `http://hyperhire-api.id/api/documentation` → Swagger UI
- `http://hyperhire-api.id/api/*` → API endpoints

---

## 📋 Features

1. ✅ List of recommended people (with pagination)
2. ✅ Like person
3. ✅ Dislike person
4. ✅ Liked people list (API only)
5. ✅ Cronjob - Email notification when person reaches 50+ likes

---

## 📖 API Endpoints

### People
- `GET /api/people` - Get all people
- `GET /api/people/recommended` - Get recommended people
- `GET /api/people/{id}` - Get person by ID
- `POST /api/people` - Create person

### Likes
- `POST /api/likes/like` - Like a person
- `POST /api/likes/dislike` - Dislike a person
- `GET /api/likes/liked-people` - Get liked people
- `GET /api/likes/disliked-people` - Get disliked people

**Full documentation**: Access Swagger UI for interactive API testing

---

## 🗄️ Database Schema

### Tables

**people**
- id, name, age, location, pictures (JSON), timestamps

**likes**
- id, people_id (FK), type (ENUM: like/dislike), timestamps

**Relationship**: One Person → Many Likes (Cascade Delete)

---

## 📧 Email Notifications

Email dikirim ke admin ketika:
1. Person mencapai 50+ likes (real-time)
2. Cronjob harian menemukan person dengan 50+ likes

**Setup**:
```env
MAIL_ADMIN_EMAIL=admin@hyperhire-api.id
```

---

## ⏰ Cronjob

**Command**:
```bash
php artisan check:person-likes-threshold
```

**Schedule**: Daily at 00:00 (Asia/Jakarta)

**Hostinger Setup**: Lihat [HOSTINGER_DEPLOYMENT.md](HOSTINGER_DEPLOYMENT.md)

---

## 🧪 Testing

### Via Swagger UI
1. Open http://localhost/api/documentation
2. Click endpoint → "Try it out"
3. Fill parameters → "Execute"
4. See response

### Via Postman
Import: `postman_collection.json`

### Via cURL
```bash
curl http://localhost/api/people/recommended
```

---

## 🛠️ Tech Stack

- **Framework**: Laravel 11.x
- **Language**: PHP 8.2+
- **Database**: MySQL 5.7+
- **API Docs**: Swagger/OpenAPI 3.0
- **Queue**: Database

---

## 📁 Project Structure

```
app/
├── Console/Commands/      # Cronjob
├── Http/Controllers/API/  # API Controllers
├── Services/              # Business Logic
├── Models/                # Database Models
├── Events/                # Events
├── Listeners/             # Event Listeners
└── Mail/                  # Email Templates

routes/
├── api.php               # API Routes
├── web.php               # Web Routes (root redirect)
└── console.php           # Cronjob Schedule

config/l5-swagger.php     # Swagger Config
```

---

## 🔧 Useful Commands

```bash
# Generate Swagger docs
php artisan l5-swagger:generate

# Run cronjob manually
php artisan check:person-likes-threshold

# Clear cache
php artisan optimize:clear

# Run queue worker
php artisan queue:work
```

---

## 📞 Support

- **Swagger UI**: Interactive API documentation
- **Postman Collection**: Import for testing
- **Deployment Guide**: [HOSTINGER_DEPLOYMENT.md](HOSTINGER_DEPLOYMENT.md)

---

**Version**: 1.0.0  
**Framework**: Laravel 11.x  
**Status**: ✅ Production Ready
