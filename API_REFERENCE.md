# 📚 WowoClean API Reference

Dokumentasi lengkap semua API endpoints dengan contoh request dan response.

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication
Semua endpoint (kecuali login) memerlukan JWT Bearer Token di header:
```
Authorization: Bearer YOUR_JWT_TOKEN
```

---

## 🔐 Authentication Endpoints

### 1. Login
**Endpoint**: `POST /login`

Dapatkan JWT token untuk authentication.

**Request Body:**
```json
{
  "email": "admin@wowoclean.com",
  "password": "password123"
}
```

**Success Response (200):**
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

**Error Response (401):**
```json
{
  "success": false,
  "message": "Email atau password salah."
}
```

**Error Response (422):**
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "email": ["Email field is required."],
    "password": ["Password field is required."]
  }
}
```

---

### 2. Get Profile
**Endpoint**: `GET /profile`

Dapatkan informasi profile user yang sedang login.

**Headers:**
```
Authorization: Bearer YOUR_JWT_TOKEN
```

**Success Response (200):**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Administrator",
    "email": "admin@wowoclean.com",
    "role": "admin",
    "created_at": "2026-05-29T12:00:00.000000Z"
  }
}
```

**Error Response (401):**
```json
{
  "success": false,
  "message": "Unauthorized: Token tidak valid atau sudah expired."
}
```

---

### 3. Logout
**Endpoint**: `POST /logout`

Logout dan invalidate JWT token.

**Headers:**
```
Authorization: Bearer YOUR_JWT_TOKEN
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Logout berhasil"
}
```

---

## 📦 Container Endpoints (Gateway)

### 1. List Containers
**Endpoint**: `GET /gateway/containers`

Dapatkan daftar semua containers dengan support search, filter, sort, dan pagination.

**Query Parameters:**
| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `search` | string | Cari by kode, lokasi, atau jenis limbah | `WC-B3` |
| `status` | string | Filter by status | `Active` |
| `jenis_limbah` | string | Filter by jenis limbah | `Chemical` |
| `sort_by` | string | Column untuk sort | `created_at` |
| `sort_order` | string | Order (asc/desc) | `desc` |
| `per_page` | integer | Items per page | `10` |
| `page` | integer | Page number | `1` |

**Example Request:**
```
GET /gateway/containers?status=Active&jenis_limbah=Chemical&per_page=10&page=1
```

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "kode_container": "WC-B3-001",
      "jenis_limbah": "Chemical",
      "kapasitas": 500,
      "lokasi": "Gudang Utama - Zona A",
      "status": "Active",
      "tracking_logs_count": 3,
      "created_at": "2026-05-01T08:00:00.000000Z",
      "updated_at": "2026-05-29T10:30:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 8
  }
}
```

---

### 2. Get Container Detail
**Endpoint**: `GET /gateway/containers/{id}`

Dapatkan detail satu container beserta tracking logs terbaru.

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `id` | integer | Container ID |

**Example Request:**
```
GET /gateway/containers/1
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "kode_container": "WC-B3-001",
    "jenis_limbah": "Chemical",
    "kapasitas": 500,
    "lokasi": "Gudang Utama - Zona A",
    "status": "Active",
    "created_at": "2026-05-01T08:00:00.000000Z",
    "updated_at": "2026-05-29T10:30:00.000000Z",
    "tracking_logs_count": 3,
    "tracking_logs": [
      {
        "id": 1,
        "container_id": 1,
        "tanggal": "2026-05-10",
        "lokasi": "Fasilitas Pengolahan",
        "catatan": "Proses pengolahan limbah dimulai",
        "operator": "Sari Dewi",
        "status_perjalanan": "Processing",
        "created_at": "2026-05-10T14:00:00.000000Z"
      }
    ]
  }
}
```

**Error Response (404):**
```json
{
  "success": false,
  "message": "Container tidak ditemukan."
}
```

---

### 3. Create Container (Admin Only)
**Endpoint**: `POST /gateway/containers`

Buat container baru.

**Request Body:**
```json
{
  "kode_container": "WC-B3-009",
  "jenis_limbah": "Chemical",
  "kapasitas": 750,
  "lokasi": "Gudang Zona I",
  "status": "Active"
}
```

**Validation Rules:**
- `kode_container`: required, string, max:50, unique
- `jenis_limbah`: required, in:Chemical|Medical|Electronic|Radioactive
- `kapasitas`: required, integer, min:10, max:10000
- `lokasi`: required, string, max:255
- `status`: optional, in:Active|Maintenance|Full|Archived

**Conditional Validation:**
- Chemical: max kapasitas 1000 kg
- Radioactive: max kapasitas 500 kg

**Success Response (201):**
```json
{
  "success": true,
  "message": "Container berhasil ditambahkan!",
  "data": {
    "id": 9,
    "kode_container": "WC-B3-009",
    "jenis_limbah": "Chemical",
    "kapasitas": 750,
    "lokasi": "Gudang Zona I",
    "status": "Active",
    "created_at": "2026-05-29T15:30:00.000000Z"
  }
}
```

**Error Response (422):**
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "kode_container": ["Kode container sudah ada."],
    "kapasitas": ["Limbah Chemical tidak boleh melebihi kapasitas 1000 kg."]
  }
}
```

**Error Response (403):**
```json
{
  "success": false,
  "message": "Forbidden: Anda tidak memiliki akses untuk melakukan aksi ini.",
  "required_role": ["admin"],
  "your_role": "user"
}
```

---

### 4. Update Container (Admin Only)
**Endpoint**: `PUT /gateway/containers/{id}`

Update data container.

**Request Body:**
```json
{
  "kode_container": "WC-B3-009-UPDATED",
  "status": "Maintenance",
  "lokasi": "Gudang Zona I Updated"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Container berhasil diupdate!",
  "data": {
    "id": 9,
    "kode_container": "WC-B3-009-UPDATED",
    "jenis_limbah": "Chemical",
    "kapasitas": 750,
    "lokasi": "Gudang Zona I Updated",
    "status": "Maintenance",
    "updated_at": "2026-05-29T15:35:00.000000Z"
  }
}
```

---

### 5. Archive Container (Admin Only)
**Endpoint**: `PATCH /gateway/containers/{id}/archive`

Ubah status container menjadi Archived.

**Success Response (200):**
```json
{
  "success": true,
  "message": "Container berhasil di-archive!",
  "data": {
    "id": 9,
    "status": "Archived",
    "updated_at": "2026-05-29T15:40:00.000000Z"
  }
}
```

---

### 6. Delete Container (Admin Only)
**Endpoint**: `DELETE /gateway/containers/{id}`

Hapus container beserta tracking logs-nya.

**Success Response (200):**
```json
{
  "success": true,
  "message": "Container berhasil dihapus!"
}
```

**Error Response (404):**
```json
{
  "success": false,
  "message": "Container tidak ditemukan."
}
```

---

## 📍 Tracking Log Endpoints (Gateway)

### 1. List Container Tracking Logs
**Endpoint**: `GET /gateway/containers/{id}/tracking-logs`

Dapatkan daftar tracking logs untuk satu container.

**Success Response (200):**
```json
{
  "success": true,
  "container": {
    "id": 1,
    "kode_container": "WC-B3-001",
    "jenis_limbah": "Chemical",
    "status": "Active"
  },
  "data": [
    {
      "id": 1,
      "container_id": 1,
      "tanggal": "2026-05-10",
      "lokasi": "Fasilitas Pengolahan",
      "catatan": "Proses pengolahan limbah dimulai",
      "operator": "Sari Dewi",
      "status_perjalanan": "Processing",
      "created_at": "2026-05-10T14:00:00.000000Z"
    }
  ]
}
```

---

### 2. Create Tracking Log (Admin Only)
**Endpoint**: `POST /gateway/containers/{id}/tracking-logs`

Tambah tracking log baru untuk container.

**Request Body:**
```json
{
  "tanggal": "2026-05-29",
  "lokasi": "Warehouse Penyimpanan",
  "status_perjalanan": "In Transit",
  "operator": "Andi Wijaya",
  "catatan": "Container dalam perjalanan ke warehouse"
}
```

**Validation Rules:**
- `tanggal`: required, date
- `lokasi`: required, string, max:255
- `status_perjalanan`: required, in:Received|In Transit|Processing|Full|Maintenance|Inspection|Monitoring|Repaired|Completed
- `operator`: required, string, max:255
- `catatan`: optional, string, max:1000

**Success Response (201):**
```json
{
  "success": true,
  "message": "Tracking log berhasil ditambahkan.",
  "data": {
    "id": 16,
    "container_id": 1,
    "tanggal": "2026-05-29",
    "lokasi": "Warehouse Penyimpanan",
    "catatan": "Container dalam perjalanan ke warehouse",
    "operator": "Andi Wijaya",
    "status_perjalanan": "In Transit",
    "created_at": "2026-05-29T16:00:00.000000Z"
  }
}
```

**Error Response (422):**
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "status_perjalanan": ["Status perjalanan tidak valid."]
  }
}
```

---

## 📊 Dashboard Endpoints (Gateway)

### Get Statistics
**Endpoint**: `GET /gateway/stats`

Dapatkan dashboard statistics.

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "total_containers": 8,
    "active": 4,
    "maintenance": 2,
    "full": 1,
    "archived": 1,
    "status_distribution": {
      "Active": 4,
      "Maintenance": 2,
      "Full": 1,
      "Archived": 1
    },
    "jenis_distribution": {
      "Chemical": 3,
      "Medical": 2,
      "Electronic": 2,
      "Radioactive": 1
    },
    "tracking_activity": {
      "2026-05-01": 2,
      "2026-05-02": 1,
      "2026-05-03": 1,
      "...": "...",
      "2026-05-29": 3
    },
    "recent_logs": [
      {
        "id": 15,
        "container_kode": "WC-B3-008",
        "lokasi": "Workshop Reparasi",
        "operator": "Bambang Suryadi",
        "status_perjalanan": "Repaired",
        "tanggal": "2026-05-20",
        "catatan": "Perbaikan seal container selesai"
      }
    ]
  }
}
```

---

## 🔑 Status Values

### Container Status
- `Active` - Kontainer aktif/siap digunakan
- `Maintenance` - Kontainer dalam perawatan
- `Full` - Kontainer penuh
- `Archived` - Kontainer sudah di-archive

### Jenis Limbah
- `Chemical` - Limbah kimia
- `Medical` - Limbah medis
- `Electronic` - Limbah elektronik
- `Radioactive` - Limbah radioaktif

### Tracking Status Perjalanan
- `Received` - Diterima
- `In Transit` - Dalam perjalanan
- `Processing` - Sedang diproses
- `Full` - Penuh
- `Maintenance` - Perawatan
- `Inspection` - Inspeksi
- `Monitoring` - Monitoring
- `Repaired` - Diperbaiki
- `Completed` - Selesai

---

## ⚠️ HTTP Status Codes

| Code | Meaning |
|------|---------|
| `200` | OK - Request berhasil |
| `201` | Created - Resource berhasil dibuat |
| `400` | Bad Request - Request tidak valid |
| `401` | Unauthorized - Token tidak valid/expired |
| `403` | Forbidden - User tidak punya akses |
| `404` | Not Found - Resource tidak ditemukan |
| `422` | Unprocessable Entity - Validasi gagal |
| `500` | Internal Server Error - Server error |

---

## 🔄 Error Response Format

Semua error response mengikuti format:
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field_name": ["error_detail_1", "error_detail_2"]
  }
}
```

---

## 📌 Rate Limiting

Tidak ada rate limiting di development. Di production, pertimbangkan:
- 60 requests per minute per IP untuk public endpoints
- 300 requests per minute per authenticated user

---

## 🔐 Authorization

**Admin Role**:
- ✅ Create, Read, Update, Delete containers
- ✅ Archive containers
- ✅ Create tracking logs
- ✅ View dashboard statistics

**User/Operator Role**:
- ✅ Read containers (list & detail)
- ✅ View dashboard statistics
- ✅ Read tracking logs
- ❌ Create/Update/Delete containers
- ❌ Create tracking logs

---

## 📞 Support

Untuk pertanyaan atau issues, silakan refer ke README.md atau hubungi support@wowoclean.com

---

**Last Updated**: May 29, 2026  
**Version**: 1.0.0
