# 🌿 WowoClean - Sistem Manajemen Limbah B3

Aplikasi web fullstack modern untuk manajemen limbah berbahaya dan beracun (B3) dengan arsitektur REST API, autentikasi JWT, dan dashboard enterprise yang menawan.

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![Laravel](https://img.shields.io/badge/Laravel-13.0-red)
![PHP](https://img.shields.io/badge/PHP-8.3-purple)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)
![License](https://img.shields.io/badge/License-MIT-green)

## 🎯 Fitur Utama

### ✅ Authentication & Authorization
- JWT Authentication dengan TTL 60 menit
- Role-based access control (Admin, Operator/User)
- Middleware authorization dengan response 403 Forbidden
- Session token storage di localStorage
- Secure logout dengan token invalidation

### ✅ API Gateway Architecture
- Semua endpoint melalui `/api/v1/gateway/*`
- Standard JSON response format
- Comprehensive request validation
- Conditional validation (kapasitas limbah per jenis)
- Error handling dengan HTTP status codes

### ✅ Container Management
- Full CRUD untuk kontainer limbah B3
- 4 Status: Active, Maintenance, Full, Archived
- Real-time search (kode, lokasi, jenis limbah)
- Dynamic filtering & sorting
- Pagination support
- Archive functionality (soft delete)
- Container detail dengan tracking history

### ✅ Tracking Logs
- Modern timeline view
- 9 status perjalanan: Received, In Transit, Processing, Full, Maintenance, Inspection, Monitoring, Repaired, Completed
- Operator & catatan tracking
- Date-based activity tracking
- Historical data preservation

### ✅ Dashboard Analytics
- Real-time statistics cards
- Pie chart distribusi status kontainer
- Line chart aktivitas tracking (30 hari)
- Recent activity logs table
- Empty state handling

### ✅ Modern Frontend
- Enterprise-grade dashboard design
- Responsive mobile layout
- Dark theme with cyan/blue accent
- Smooth animations & transitions
- Toast notifications
- Modal dialogs dengan confirmation
- Skeleton loading states
- Breadcrumb navigation
- Sidebar navigation dengan collapse

### ✅ API Documentation
- Full Swagger/OpenAPI documentation
- Interactive API explorer
- Bearer token authentication
- Request/response examples
- Accessible di `/api/documentation`

## 🛠 Tech Stack

### Backend
- **Framework**: Laravel 13.0
- **Language**: PHP 8.3+
- **Database**: MySQL 8.0+
- **Authentication**: JWT (php-open-source-saver/jwt-auth)
- **API Documentation**: L5-Swagger (darkaonline/l5-swagger)
- **ORM**: Eloquent with relationships

### Frontend
- **Markup**: HTML5 semantic
- **Styling**: CSS3 with CSS Variables
- **JavaScript**: ES6+ Vanilla JS
- **HTTP Client**: Axios
- **UI Framework**: Bootstrap 5
- **Charts**: Chart.js
- **Icons**: Font Awesome 6

## 📋 Requirements

- PHP 8.3 atau lebih tinggi
- MySQL 8.0 atau lebih tinggi
- Composer (Dependency Manager PHP)
- Node.js & npm (Build tools)
- Laravel 13.0
- Laragon / XAMPP / Docker

## 🚀 Installation & Setup

### 1. Prerequisites
```bash
# Pastikan MySQL running di Laragon/XAMPP
# Buka folder project
cd c:/laragon/www/wowoclean/UTP_HAZEL_TIS_26
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT secret (PENTING!)
php artisan jwt:secret
```

### 4. Database Configuration
Edit file `.env` dan sesuaikan:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wowoclean
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations & Seeders
```bash
# Create tables
php artisan migrate

# Seed dummy data (admin & user accounts, containers, tracking logs)
php artisan db:seed

# OR run fresh (reset + migrate + seed)
php artisan migrate:fresh --seed
```

### 6. Generate Swagger Documentation
```bash
php artisan l5-swagger:generate
```

### 7. Set Permissions (Linux/Mac Only)
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## ▶️ Menjalankan Aplikasi

### Terminal 1: Start Laravel Server
```bash
php artisan serve
```
Server akan running di `http://localhost:8000`

### Terminal 2 (Optional): Start Vite Dev Server
```bash
npm run dev
```

## 📱 Akses Aplikasi

| Komponen | URL |
|----------|-----|
| Dashboard | http://localhost:8000/dashboard |
| Login | http://localhost:8000/login |
| API Docs | http://localhost:8000/api/documentation |
| API Base | http://localhost:8000/api/v1 |

## 👤 Akun Demo (Default Credentials)

### Admin Account
```
Email: admin@wowoclean.com
Password: password123
Role: Administrator (Full Access)
Permissions: Create, Read, Update, Delete, Archive
```

### Operator Account
```
Email: operator@wowoclean.com
Password: password123
Role: User/Operator (Read-Only)
Permissions: View Dashboard, Read Containers, Read Logs
```

## 🔌 API Endpoints

### Authentication
```
POST   /api/v1/login                      Login & Get JWT Token
GET    /api/v1/profile                    Get User Profile
POST   /api/v1/logout                     Logout (Invalidate Token)
```

### Containers (via Gateway)
```
GET    /api/v1/gateway/containers         List Containers (search, filter, sort, paginate)
GET    /api/v1/gateway/containers/{id}    Get Container Detail
POST   /api/v1/gateway/containers         Create Container (Admin Only)
PUT    /api/v1/gateway/containers/{id}    Update Container (Admin Only)
PATCH  /api/v1/gateway/containers/{id}/archive    Archive Container (Admin Only)
DELETE /api/v1/gateway/containers/{id}    Delete Container (Admin Only)
```

### Tracking Logs (via Gateway)
```
GET    /api/v1/gateway/containers/{id}/tracking-logs     List Logs
POST   /api/v1/gateway/containers/{id}/tracking-logs      Create Log (Admin Only)
```

### Dashboard
```
GET    /api/v1/gateway/stats              Get Dashboard Statistics
```

## 🧪 Testing API dengan cURL

### 1. Login & Dapatkan Token
```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@wowoclean.com",
    "password": "password123"
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "Login berhasil",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": {
    "id": 1,
    "name": "Administrator",
    "email": "admin@wowoclean.com",
    "role": "admin"
  }
}
```

### 2. Get Profile
```bash
curl -X GET http://localhost:8000/api/v1/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 3. List Containers
```bash
curl -X GET "http://localhost:8000/api/v1/gateway/containers?status=Active&per_page=10" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 4. Create Container
```bash
curl -X POST http://localhost:8000/api/v1/gateway/containers \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "kode_container": "WC-B3-009",
    "jenis_limbah": "Chemical",
    "kapasitas": 750,
    "lokasi": "Gudang Zona I",
    "status": "Active"
  }'
```

## 📊 Database Schema

### users
```
- id (PK)
- name
- email (unique)
- email_verified_at
- password (hashed)
- role (admin|user)
- remember_token
- timestamps
```

### containers
```
- id (PK)
- kode_container (unique)
- jenis_limbah (Chemical|Medical|Electronic|Radioactive)
- kapasitas (kg)
- lokasi
- status (Active|Maintenance|Full|Archived)
- timestamps
```

### tracking_logs
```
- id (PK)
- container_id (FK)
- tanggal (date)
- lokasi
- catatan (text, nullable)
- operator
- status_perjalanan
- timestamps
```

## 📁 Project Structure

```
wowoclean/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php (Login, Profile, Logout)
│   │   │   ├── ContainerController.php (CRUD Operations)
│   │   │   ├── TrackingLogController.php (Tracking Logs)
│   │   │   ├── StatsController.php (Dashboard Stats)
│   │   │   └── Controller.php (Swagger Annotations)
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php (Authorization)
│   ├── Models/
│   │   ├── User.php (JWTSubject)
│   │   ├── Container.php (HasMany TrackingLogs)
│   │   └── TrackingLog.php (BelongsTo Container)
│   └── Providers/
│       └── AppServiceProvider.php
├── config/
│   ├── auth.php (JWT configured)
│   ├── jwt.php (JWT config)
│   ├── l5-swagger.php (Swagger config)
│   └── app.php, database.php, ...
├── database/
│   ├── migrations/
│   │   ├── create_users_table
│   │   ├── create_containers_table
│   │   └── create_tracking_logs_table
│   ├── factories/
│   └── seeders/
│       └── DatabaseSeeder.php (Dummy Data)
├── resources/
│   ├── views/
│   │   ├── app.blade.php (Dashboard)
│   │   └── login.blade.php (Login Page)
│   ├── css/
│   │   └── app.css
│   └── js/
│       └── app.js (Axios + Frontend Logic)
├── routes/
│   ├── api.php (API Routes v1)
│   └── web.php (Web Routes)
├── storage/ (Logs, Cache)
├── tests/
├── composer.json
├── package.json
├── vite.config.js
├── .env.example
├── artisan
└── README.md
```

## 🔒 Security Features

- ✅ JWT Token-based authentication
- ✅ Role-based authorization middleware
- ✅ CORS protection
- ✅ CSRF protection
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection
- ✅ Input validation dengan custom rules
- ✅ Password hashing (bcrypt)
- ✅ HTTP-only token storage
- ✅ Token expiration & refresh

## 📝 Validation Rules

### Container Creation
```
- kode_container: required, string, max:50, unique
- jenis_limbah: required, in:Chemical|Medical|Electronic|Radioactive
- kapasitas: required, integer, min:10, max:10000
- lokasi: required, string, max:255
- status: optional, in:Active|Maintenance|Full|Archived

Conditional Rules:
- Chemical: max kapasitas 1000 kg
- Radioactive: max kapasitas 500 kg
- Medical & Electronic: max 10000 kg
```

### Tracking Log Creation
```
- tanggal: required, date
- lokasi: required, string, max:255
- status_perjalanan: required, in:Received|In Transit|Processing|Full|Maintenance|Inspection|Monitoring|Repaired|Completed
- operator: required, string, max:255
- catatan: optional, string, max:1000
```

## 🛠 Useful Commands

```bash
# Database
php artisan migrate              # Run migrations
php artisan migrate:rollback    # Rollback
php artisan migrate:fresh       # Fresh migration
php artisan db:seed             # Seed database
php artisan tinker              # Interactive shell

# JWT
php artisan jwt:secret          # Generate JWT secret

# Cache
php artisan cache:clear         # Clear cache
php artisan config:cache        # Cache config

# Swagger
php artisan l5-swagger:generate # Generate Swagger docs

# Testing
php artisan test                # Run tests
php artisan test --filter=TestName
```

## 🐛 Troubleshooting

| Masalah | Solusi |
|---------|--------|
| JWT Secret tidak generate | Jalankan: `php artisan jwt:secret` |
| Database connection error | Cek .env credentials, pastikan MySQL running |
| Swagger docs not showing | Jalankan: `php artisan l5-swagger:generate` |
| Permission denied errors | `chmod -R 775 storage bootstrap/cache` |
| Token expired | Token auto-refresh atau login ulang |
| CORS errors | Cek config/cors.php, tambahkan frontend URL |

## 📄 License

MIT License © 2026 WowoClean

---

**Last Updated**: May 29, 2026  
**Version**: 1.0.0  
**Support**: support@wowoclean.com
