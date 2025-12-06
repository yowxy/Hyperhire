# HyperHire Backend API

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php)](https://php.net)

Backend API untuk aplikasi dating HyperHire dengan fitur like/dislike, rekomendasi person, dan automated email notifications.

## ✨ Features

- ✅ **List Recommended People** - Pagination support, filtering people yang belum di-like/dislike
- ✅ **Like/Dislike Person** - With duplicate handling & automatic email trigger
- ✅ **Liked People List** - API untuk mendapatkan semua liked people
- ✅ **Automated Email Notifications** - Email ke admin ketika person mencapai 50+ likes
- ✅ **Daily Cronjob** - Scheduled task untuk monitoring popular people

## 🚀 Quick Start

### Requirements
- PHP 8.2+
- MySQL 5.7+
- Composer
- Laravel 11.x

### Installation

```bash
# 1. Clone & Install
cd c:\laragon\www\be-hyperhire
composer install

# 2. Setup Environment
cp .env.example .env
php artisan key:generate

# 3. Configure Database & Email in .env
# Update DB credentials & add:
# MAIL_ADMIN_EMAIL="admin@hyperhire.com"

# 4. Run Migrations
php artisan migrate

# 5. Seed Sample Data (Optional)
php artisan db:seed --class=PeopleSeeder

# 6. Run Queue Worker
php artisan queue:work
```

**📖 Untuk panduan lengkap, baca [QUICKSTART.md](QUICKSTART.md)**

---

## 📚 Documentation

| Document | Description |
|----------|-------------|
| [📖 QUICKSTART.md](QUICKSTART.md) | Setup dalam 5 menit + test scenarios |
| [📋 FEATURES.md](FEATURES.md) | Dokumentasi lengkap API & architecture |
| [⚙️ ENV_SETUP.md](ENV_SETUP.md) | Panduan konfigurasi environment |
| [📊 SUMMARY.md](SUMMARY.md) | Overview & highlights implementasi |
| [🗄️ DATABASE_SCHEMA.md](DATABASE_SCHEMA.md) | Database schema & ERD |
| [📖 SWAGGER_GUIDE.md](SWAGGER_GUIDE.md) | Swagger/OpenAPI documentation guide |
| [🏗️ INFRASTRUCTURE.md](INFRASTRUCTURE.md) | Infrastructure & deployment guide |
| [🔧 postman_collection.json](postman_collection.json) | Postman collection untuk testing |

### 🌐 Interactive API Documentation

**Swagger UI** tersedia di:
```
http://localhost/api/documentation
```

Anda bisa test semua API endpoint langsung dari browser dengan Swagger UI yang interaktif!

---

## 🎯 API Endpoints

### Person
```
GET    /api/people                  - Get all people with pagination
GET    /api/people/recommended      - Get recommended people
GET    /api/people/{id}             - Get person by ID
POST   /api/people                  - Create new person
```

### Likes
```
POST   /api/likes/like              - Like a person
POST   /api/likes/dislike           - Dislike a person
GET    /api/likes/liked-people      - Get all liked people
GET    /api/likes/disliked-people   - Get all disliked people
```

**Example:**
```bash
# Get recommended people
curl http://localhost/api/people/recommended?per_page=10

# Like a person
curl -X POST http://localhost/api/likes/like \
  -H "Content-Type: application/json" \
  -d '{"person_id": 1}'
```

---

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────┐
│               API Layer                          │
│  PersonController  │  LikesController           │
└───────────────┬─────────────────────────────────┘
                │
┌───────────────▼─────────────────────────────────┐
│            Service Layer                         │
│  PersonService  │  LikesService                 │
└───────────────┬─────────────────────────────────┘
                │
┌───────────────▼─────────────────────────────────┐
│            Data Layer                            │
│  Person Model  │  Likes Model                   │
└─────────────────────────────────────────────────┘

         Event System (Email Notifications)
PersonLikesThreshold → Listener → Send Email
```

**Keterangan:**
- **Controller**: Handle HTTP requests & validation
- **Service**: Business logic & data processing
- **Model**: Database interaction via Eloquent
- **Events**: Event-driven email notifications

---

## 📧 Email Notifications

System otomatis mengirim email ke admin dalam 2 cara:

1. **Real-time**: Ketika person di-like dan total likes mencapai ≥50
2. **Daily Cronjob**: Setiap hari pukul 00:00 (Asia/Jakarta)

**Command:**
```bash
php artisan check:person-likes-threshold
```

**Email Preview:**
- Beautiful HTML template dengan gradient design
- Informasi lengkap person (nama, umur, lokasi, likes count)
- Professional layout

---

## 🔧 Testing

### Import Postman Collection
1. Import `postman_collection.json` ke Postman
2. Set variable `base_url` = `http://localhost`
3. Test semua endpoints

### Manual Testing
```bash
# Test cronjob
php artisan check:person-likes-threshold

# Test API
curl http://localhost/api/people/recommended
```

---

## 📂 Project Structure

```
app/
├── Console/Commands/          # Cronjob commands
├── Events/                    # Event classes
├── Http/Controllers/API/      # API Controllers
├── Listeners/                 # Event listeners
├── Mail/                      # Mailable classes
├── Models/                    # Eloquent models
├── Providers/                 # Service providers
└── Services/                  # Business logic services

database/
├── migrations/                # Database migrations
└── seeders/                   # Database seeders

routes/
├── api.php                    # API routes
└── console.php                # Console routes & schedules

resources/views/emails/        # Email templates
```

---

## 🛠️ Tech Stack

- **Framework**: Laravel 11.x
- **Language**: PHP 8.2+
- **Database**: MySQL
- **Queue**: Database driver
- **Email**: SMTP / Log driver
- **Architecture**: Service Layer Pattern + Event-Driven

---

## 📊 Database Schema

### People Table
```
- id (primary key)
- name (string)
- age (integer)
- location (string)
- pictures (json)
- timestamps
```

### Likes Table
```
- id (primary key)
- people_id (foreign key → people.id)
- type (enum: 'like', 'dislike')
- timestamps
```

---

## 🎉 Features Breakdown

### ✅ Pagination
- Smart pagination dengan max 100 items per page
- Complete metadata (total, current_page, last_page, etc)
- Query parameter: `?per_page=10`

### ✅ Event-Driven Email
- Automatic trigger when threshold reached
- Queue support untuk performance
- Beautiful HTML email template

### ✅ Cronjob Automation
- Scheduled daily at midnight
- Timezone configurable (default: Asia/Jakarta)
- Auto email on failure

### ✅ Error Handling
- Comprehensive validation
- Proper HTTP status codes
- Consistent JSON response format

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---

## 📄 License

This project is licensed under the MIT License.

---

## 👨‍💻 Developer

Made with ❤️ for **HyperHire**

---

## 📞 Support

Untuk pertanyaan atau bantuan:
- 📖 Baca dokumentasi di folder `docs/`
- 📧 Email: admin@hyperhire.com

---

## 🔗 Links

- [Laravel Documentation](https://laravel.com/docs)
- [API Documentation](FEATURES.md)
- [Quick Start Guide](QUICKSTART.md)

---

**Status**: ✅ Production Ready | Version 1.0.0
