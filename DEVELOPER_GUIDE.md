# 👨‍💻 WowoClean - Developer Guide

Panduan lengkap untuk developers yang ingin memahami dan mengembangkan WowoClean lebih lanjut.

## 📖 Table of Contents
1. [Project Structure](#project-structure)
2. [Architecture](#architecture)
3. [Code Conventions](#code-conventions)
4. [Adding New Features](#adding-new-features)
5. [Testing Guidelines](#testing-guidelines)
6. [Deployment](#deployment)

---

## 📂 Project Structure

```
wowoclean/
├── app/                          # Application logic
│   ├── Http/
│   │   ├── Controllers/          # API endpoints
│   │   │   ├── AuthController.php
│   │   │   ├── ContainerController.php
│   │   │   ├── TrackingLogController.php
│   │   │   ├── StatsController.php
│   │   │   └── Controller.php (base + Swagger)
│   │   └── Middleware/
│   │       └── RoleMiddleware.php (authorization)
│   ├── Models/                   # Database models
│   │   ├── User.php
│   │   ├── Container.php
│   │   └── TrackingLog.php
│   └── Providers/
│       └── AppServiceProvider.php
├── bootstrap/                    # Application bootstrap
│   └── providers.php
├── config/                       # Configuration files
│   ├── app.php
│   ├── auth.php
│   ├── jwt.php
│   ├── l5-swagger.php
│   └── ...
├── database/
│   ├── migrations/               # Database schema
│   │   ├── create_users_table.php
│   │   ├── create_containers_table.php
│   │   └── create_tracking_logs_table.php
│   ├── factories/                # Model factories for testing
│   │   └── UserFactory.php
│   └── seeders/                  # Database seeders
│       └── DatabaseSeeder.php
├── resources/
│   ├── views/                    # Blade templates
│   │   ├── app.blade.php (main dashboard)
│   │   └── login.blade.php
│   ├── css/                      # Stylesheets (minimal, inline in HTML)
│   │   └── app.css
│   └── js/                       # JavaScript files
│       └── app.js (main application logic)
├── routes/
│   ├── api.php                   # API routes (v1 with gateway)
│   └── web.php                   # Web routes
├── storage/                      # Logs, cache, uploads
│   ├── logs/
│   ├── app/
│   └── framework/
├── tests/                        # Unit & feature tests
│   ├── Unit/
│   └── Feature/
├── vendor/                       # Composer dependencies (auto-generated)
├── node_modules/                 # NPM dependencies (auto-generated)
├── .env.example                  # Environment template
├── composer.json                 # PHP dependencies
├── package.json                  # NPM dependencies
├── artisan                       # Laravel CLI
└── README.md                     # Documentation
```

---

## 🏗️ Architecture

### Request Flow

```
┌─────────────────────────────────────────────────────────────┐
│                      Frontend (HTML/JS)                     │
│  - Login page                                                │
│  - Dashboard with charts                                     │
│  - Container CRUD forms                                      │
│  - Responsive design                                         │
└────────────────────────┬────────────────────────────────────┘
                         │
                    Axios HTTP
                    (JWT Bearer)
                         │
┌────────────────────────▼────────────────────────────────────┐
│                   Laravel API Layer                         │
│  /api/v1/login                                               │
│  /api/v1/profile                                             │
│  /api/v1/logout                                              │
│  /api/v1/gateway/containers (CRUD)                           │
│  /api/v1/gateway/tracking-logs (CRUD)                        │
│  /api/v1/gateway/stats                                       │
└────────────────────────┬────────────────────────────────────┘
                         │
            ┌────────────┼────────────┐
            │            │            │
      Middleware    Controllers    Models
      - JWT Auth    - Validation   - ORM
      - Role Auth   - Processing   - Relations
      - CORS        - Response      - Queries
            │            │            │
            └────────────┼────────────┘
                         │
┌────────────────────────▼────────────────────────────────────┐
│                    Database Layer                           │
│  - MySQL 8.0+                                                │
│  - Tables: users, containers, tracking_logs                 │
│  - Eloquent ORM                                              │
└─────────────────────────────────────────────────────────────┘
```

### API Gateway Pattern

```
/api/v1/gateway/*  ← All business logic routes
                    ├─ /containers (CRUD)
                    ├─ /containers/{id}/tracking-logs
                    └─ /stats

/api/v1/*          ← Auth routes (public + protected)
                    ├─ /login (public)
                    ├─ /profile (protected)
                    └─ /logout (protected)
```

---

## 💻 Code Conventions

### Naming Conventions

**Controllers**
```php
// CamelCase with Controller suffix
ContainerController.php
TrackingLogController.php
```

**Methods**
```php
// Resource methods follow REST conventions
- index()      // GET list
- show()       // GET detail
- store()      // POST create
- update()     // PUT update
- destroy()    // DELETE delete
- archive()    // PATCH custom
```

**Models**
```php
// Singular, CamelCase
class Container extends Model
class User extends Model
class TrackingLog extends Model
```

**Routes**
```php
// RESTful naming
Route::get('/containers')           // index
Route::get('/containers/{id}')      // show
Route::post('/containers')          // store
Route::put('/containers/{id}')      // update
Route::patch('/containers/{id}/archive')  // custom
Route::delete('/containers/{id}')   // destroy
```

### Code Style

**PHP**
```php
// Use type hints
public function store(Request $request): JsonResponse

// Validate early
$validator = Validator::make($request->all(), [
    'field' => 'required|string|max:255'
]);

// Return standard JSON
return response()->json([
    'success' => true,
    'message' => 'Success message',
    'data' => $data
], 200);
```

**JavaScript**
```javascript
// Use arrow functions
const loadData = () => {
    // arrow function code
}

// Use Axios for HTTP
axios.get(url, {headers: {...}})
    .then(response => {})
    .catch(error => {})

// Use async/await
async function loadData() {
    try {
        const response = await axios.get(url);
    } catch (error) {
        console.error(error);
    }
}
```

---

## ➕ Adding New Features

### 1. Add New API Endpoint

**Step 1**: Create database table (if needed)
```bash
php artisan make:migration create_new_table
# Edit migration file with schema
php artisan migrate
```

**Step 2**: Create Model
```bash
php artisan make:model NewModel
# Add relationships and fillable properties
```

**Step 3**: Create Controller
```bash
php artisan make:controller NewModelController
# Add CRUD methods with Swagger annotations
```

**Step 4**: Add Routes
```php
// routes/api.php
Route::middleware('auth:api')->group(function () {
    Route::prefix('gateway')->group(function () {
        Route::get('/new-models', [NewModelController::class, 'index']);
        Route::post('/new-models', [NewModelController::class, 'store']);
        // ... etc
    });
});
```

**Step 5**: Add Swagger Annotations
```php
#[OA\Get(
    path: '/api/v1/gateway/new-models',
    summary: 'List new models',
    tags: ['New Models'],
    security: [['bearerAuth' => []]]
)]
public function index() { ... }
```

### 2. Add New Frontend Page

**Step 1**: Add HTML in app.blade.php
```html
<div id="newPage" class="page-content" style="display: none;">
    <!-- Your page content here -->
</div>
```

**Step 2**: Add JavaScript in app.js
```javascript
function loadNewPage() {
    axios.get('/api/v1/gateway/new-models')
        .then(response => {
            // Update page with data
        });
}
```

**Step 3**: Add navigation link
```html
<li>
    <a href="#new-page" class="nav-link" onclick="navigateTo('new')">
        <i class="fas fa-icon"></i>
        <span>New Page</span>
    </a>
</li>
```

---

## 🧪 Testing Guidelines

### Testing API Endpoints

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=AuthTest

# Run with coverage
php artisan test --coverage
```

### Manual Testing with Postman

1. Import `WowoClean_API.postman_collection.json`
2. Login endpoint to get token
3. Set token in Postman variables
4. Test other endpoints

### API Testing Checklist

- [ ] Test login with correct credentials
- [ ] Test login with wrong credentials
- [ ] Test 403 Forbidden for unauthorized users
- [ ] Test 404 Not Found for non-existent resources
- [ ] Test 422 Validation Failed with invalid data
- [ ] Test pagination, search, filter, sort
- [ ] Test CRUD operations
- [ ] Test role-based access control

---

## 🚀 Deployment

### Pre-Deployment Checklist

```bash
# 1. Update .env for production
APP_DEBUG=false
APP_ENV=production

# 2. Generate optimized config
php artisan config:cache
php artisan route:cache

# 3. Run migrations on production database
php artisan migrate --force

# 4. Generate Swagger docs
php artisan l5-swagger:generate

# 5. Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 6. Start server
php artisan serve --host=0.0.0.0 --port=8000
```

### Environment Variables for Production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_HOST=your-db-host
DB_DATABASE=wowoclean_prod
DB_USERNAME=prod_user
DB_PASSWORD=strong_password

JWT_TTL=1440  # 24 hours for production
JWT_SECRET=your-production-secret

CORS_ALLOWED_ORIGINS=https://yourdomain.com
```

---

## 📝 Git Workflow

```bash
# Create feature branch
git checkout -b feature/new-feature

# Make changes and commit
git add .
git commit -m "feat: add new feature"

# Push to remote
git push origin feature/new-feature

# Create pull request and merge
```

### Commit Message Format

```
feat: add new feature
fix: fix bug
docs: update documentation
refactor: refactor code
test: add tests
chore: update dependencies
```

---

## 🔍 Debugging

### Enable Debug Mode

```env
APP_DEBUG=true  # .env
```

### Use Laravel Tinker

```bash
php artisan tinker

# In tinker shell
> $user = App\Models\User::find(1);
> $user->name;
> $user->containers()->get();
```

### Check Logs

```bash
# Real-time logs
tail -f storage/logs/laravel.log

# Clear logs
php artisan cache:clear
```

### JavaScript Console

Open browser DevTools (F12) and use Console to debug JavaScript:
```javascript
console.log(apiToken);
console.error(error);
```

---

## 📚 Dependencies

### PHP Packages (Composer)

```json
{
  "require": {
    "php": "^8.3",
    "laravel/framework": "^13.0",
    "php-open-source-saver/jwt-auth": "*",
    "darkaonline/l5-swagger": "*"
  }
}
```

### Frontend Libraries (CDN)

- Bootstrap 5.3.0 - UI Framework
- Axios 1.6.2 - HTTP Client
- Chart.js 4.4.0 - Charts
- Font Awesome 6 - Icons

---

## 🔐 Security Best Practices

1. **Never commit .env file** - Use .env.example
2. **Use HTTPS in production** - Enable SSL/TLS
3. **Validate all inputs** - Server-side validation essential
4. **Hash passwords** - Use bcrypt (Laravel default)
5. **Rotate JWT secret** - Periodically change JWT_SECRET
6. **Enable CORS properly** - Whitelist specific domains
7. **Use prepared statements** - Eloquent ORM handles this
8. **Log security events** - Monitor failed logins, unauthorized access

---

## 📞 Getting Help

- Check README.md
- Read API_REFERENCE.md
- Check controller comments
- Test with Postman collection
- Review Laravel documentation (https://laravel.com)

---

## 🎯 Development Workflow

```
1. Create feature branch
2. Write tests (optional but recommended)
3. Implement feature in backend
4. Add Swagger annotations
5. Test with Postman
6. Implement frontend
7. Test full flow
8. Document changes
9. Create pull request
10. Merge to main
```

---

**Version**: 1.0.0  
**Last Updated**: May 29, 2026  
**Framework**: Laravel 13  
**PHP**: 8.3+
