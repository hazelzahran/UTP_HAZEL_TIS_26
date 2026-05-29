# 🚀 WowoClean - Quick Start Guide

Panduan cepat untuk setup dan menjalankan aplikasi WowoClean dalam 5 menit.

## Prerequisites ✅
- PHP 8.3+
- MySQL (Laragon/XAMPP running)
- Composer
- Node.js & npm

## 5-Minute Setup

### Step 1: Navigate to Project
```bash
cd c:/laragon/www/wowoclean/UTP_HAZEL_TIS_26
```

### Step 2: Install Dependencies
```bash
composer install
npm install
```
⏱️ **Waktu: ~2 menit** (tergantung internet)

### Step 3: Setup Environment
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

### Step 4: Database Setup
```bash
php artisan migrate:fresh --seed
php artisan l5-swagger:generate
```
⏱️ **Waktu: ~30 detik**

### Step 5: Run Server
```bash
php artisan serve
```

✅ **DONE!** Server siap di `http://localhost:8000`

---

## 🎯 Akses Aplikasi

| Fitur | URL |
|-------|-----|
| 🎨 Dashboard | http://localhost:8000/dashboard |
| 🔐 Login | http://localhost:8000/login |
| 📚 API Docs | http://localhost:8000/api/documentation |
| ⚡ API Base | http://localhost:8000/api/v1 |

---

## 👤 Default Login

### Admin Account
```
Email: admin@wowoclean.com
Password: password123
```
**Access**: Create, Read, Update, Delete, Archive Container & Tracking Logs

### Operator Account
```
Email: operator@wowoclean.com
Password: password123
```
**Access**: View Dashboard, Read Containers, Read Tracking Logs (Read-Only)

---

## 🧪 Quick API Test

### 1. Login & Get Token
```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@wowoclean.com","password":"password123"}'
```

**Response:**
```json
{
  "success": true,
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "user": {...}
}
```

### 2. Copy Token & Test Other Endpoints
```bash
# Replace YOUR_TOKEN with token dari response di atas
curl -X GET http://localhost:8000/api/v1/profile \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 3. Get Containers
```bash
curl -X GET "http://localhost:8000/api/v1/gateway/containers?per_page=10" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 📁 Project Structure (Simple Overview)

```
📦 wowoclean
├── 🗄️ app/
│   ├── Http/Controllers/ (API Endpoints)
│   ├── Http/Middleware/ (Authorization)
│   └── Models/ (Database Models)
├── 🗂️ resources/views/
│   ├── app.blade.php (Dashboard)
│   └── login.blade.php (Login Page)
├── 📜 routes/api.php (API Routes)
├── 🗄️ database/migrations/ (Schema)
├── 💾 storage/ (Logs, Cache)
└── 📋 .env (Configuration)
```

---

## 🔧 Useful Commands

```bash
# Database
php artisan migrate              # Run migrations
php artisan db:seed             # Seed data
php artisan migrate:fresh --seed # Reset + fresh seed

# JWT
php artisan jwt:secret          # Generate JWT secret

# Cache
php artisan cache:clear         # Clear all caches

# Swagger
php artisan l5-swagger:generate # Generate API docs

# Development
php artisan serve               # Start dev server
php artisan tinker              # Interactive shell
```

---

## 🐛 Common Issues

### Issue: "SQLSTATE[HY000] [2002] Connection refused"
**Solution**: Pastikan MySQL running di Laragon/XAMPP
```bash
# Di Laragon, klik "Start All" atau start MySQL saja
```

### Issue: "JWT_SECRET not set"
**Solution**: Jalankan command JWT secret
```bash
php artisan jwt:secret
```

### Issue: "App key not set"
**Solution**: Generate app key
```bash
php artisan key:generate
```

### Issue: Swagger docs tidak muncul
**Solution**: Generate swagger docs
```bash
php artisan l5-swagger:generate
php artisan cache:clear
```

### Issue: Login gagal dengan "Invalid credentials"
**Solution**: Pastikan seeder sudah dijalankan
```bash
php artisan db:seed
```

---

## 📝 Database Schema

### Users (Akun Login)
- Email: admin@wowoclean.com, operator@wowoclean.com
- Password: semua password123 (hashed)
- Role: admin atau user

### Containers (Kontainer Limbah)
- 8 Dummy data dengan berbagai status
- Status: Active, Maintenance, Full, Archived
- Jenis: Chemical, Medical, Electronic, Radioactive

### Tracking Logs (Histori Perjalanan)
- ~15 logs per container
- Status perjalanan: Received, In Transit, Processing, dll

---

## 🎓 Learning Resources

### Dokumentasi
- **API Docs**: http://localhost:8000/api/documentation (Swagger UI)
- **README**: Baca `README.md` untuk dokumentasi lengkap

### Testing API
- **Postman Collection**: Import file `WowoClean_API.postman_collection.json`
- **cURL**: Gunakan command di atas

### Frontend Features
- ✅ Real-time search
- ✅ Dynamic filtering & sorting
- ✅ Pagination
- ✅ Responsive design
- ✅ Toast notifications
- ✅ Modal dialogs
- ✅ Dark theme
- ✅ Dashboard charts

---

## 💡 Next Steps

1. **Explore Dashboard**: Login dan navigasi dashboard untuk lihat analytics
2. **Test API**: Gunakan Postman atau cURL untuk test semua endpoints
3. **Read Documentation**: Baca README.md untuk dokumentasi lengkap
4. **Check Code**: Review controllers di `app/Http/Controllers/`
5. **Customize**: Modify sesuai kebutuhan Anda

---

## 📞 Support

- 📧 Email: support@wowoclean.com
- 📚 Docs: http://localhost:8000/api/documentation
- 🐛 Issues: Check README.md Troubleshooting section

---

**Happy Coding! 🚀**

*Last Updated: May 29, 2026*
*Version: 1.0.0*
