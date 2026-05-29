# WowoClean Enterprise Dashboard

Sistem Manajemen Limbah B3 berbasis REST API modern dengan Laravel 13 dan SPA HTML/JS.

## Tech Stack
- **Backend**: Laravel 13, MySQL, Eloquent ORM
- **Authentication**: JWT (JSON Web Token) via `php-open-source-saver/jwt-auth`
- **Documentation**: Swagger/OpenAPI via `darkaonline/l5-swagger`
- **Frontend**: HTML5, Vanilla JS, Bootstrap 5, Axios, Chart.js, SweetAlert2

## Fitur Utama
1. **Role-based JWT Authentication** (Admin & User)
2. **API Gateway Architecture** (`/api/v1/gateway/*`)
3. **Container Management** (Full CRUD, Search, Filter, Sort, Pagination)
4. **Tracking Logs** (Timeline perjalanan limbah)
5. **Modern Dashboard Analytics** (Statistik & Chart)

---

## 🚀 Cara Setup & Menjalankan Project

### 1. Persiapan Database
Pastikan server MySQL (melalui Laragon/XAMPP) sudah menyala.
Aplikasi menggunakan database bernama `wowoclean` sesuai konfigurasi `.env`.

### 2. Instalasi Dependensi
Jalankan perintah berikut di terminal (berada di folder project):
```bash
composer install
```

### 3. Generate Application Key & JWT Secret
```bash
php artisan key:generate
php artisan jwt:secret
```

### 4. Setup Database (Migration & Seeder)
Perintah ini akan membuat semua tabel dan mengisi data *dummy* (termasuk akun admin & user).
```bash
php artisan migrate:fresh --seed
```

### 5. Generate Dokumentasi Swagger
Wajib dijalankan agar halaman dokumentasi OpenAPI bisa diakses.
```bash
php artisan l5-swagger:generate
```

### 6. Jalankan Local Server
```bash
php artisan serve
```

---

## 🔑 Akun Uji Coba (Seeder)

Gunakan akun berikut untuk login di Frontend atau Swagger:

**1. Administrator (Full Access)**
- Email: `admin@wowoclean.com`
- Password: `password123`
- Akses: Create, Read, Update, Delete, Archive, Add Tracking Log

**2. User / Operator Lapangan (Read-Only)**
- Email: `operator@wowoclean.com`
- Password: `password123`
- Akses: View dashboard, read containers, read tracking logs

---

## 📚 Akses Aplikasi

### Frontend (User Interface)
Buka di browser:
👉 **[http://localhost:8000](http://localhost:8000)** (atau via virtual host Laragon Anda)

### Swagger (API Documentation)
Buka di browser:
👉 **[http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)**

---

## 🧪 Testing API Endpoint via Postman

Anda bisa melakukan testing manual dengan endpoint berikut:

**1. Login (Dapatkan Token JWT)**
- **Method**: `POST`
- **URL**: `http://localhost:8000/api/v1/login`
- **Body** (JSON):
  ```json
  {
      "email": "admin@wowoclean.com",
      "password": "password123"
  }
  ```
> Copy value `token` dari response.

**2. Get All Containers (Mendukung Search, Filter, Pagination)**
- **Method**: `GET`
- **URL**: `http://localhost:8000/api/v1/gateway/containers?per_page=10`
- **Headers**: 
  - `Authorization`: `Bearer <token_anda_disini>`

**3. Add New Container (Admin Only)**
- **Method**: `POST`
- **URL**: `http://localhost:8000/api/v1/gateway/containers`
- **Headers**:
  - `Authorization`: `Bearer <token_anda_disini>`
- **Body** (JSON):
  ```json
  {
      "kode_container": "WC-TEST-001",
      "jenis_limbah": "Chemical",
      "kapasitas": 500,
      "lokasi": "Gudang Z"
  }
  ```
