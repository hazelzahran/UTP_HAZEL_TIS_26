<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WowoClean - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #001f3f;
            --secondary: #00d4ff;
            --dark-bg: #0a1428;
            --card-bg: #1a2a4a;
            --border-color: #2d3e5f;
            --text-light: #e9ecef;
            --text-muted: #a8b5c8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #0f1f35 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: var(--text-light);
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-logo {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--secondary), #00a8d8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 20px;
            color: var(--primary);
        }

        .login-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 10px;
        }

        .login-subtitle {
            font-size: 14px;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 10px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            background: rgba(0, 212, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-light);
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--secondary);
            background: rgba(0, 212, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1);
        }

        .form-group input::placeholder {
            color: var(--text-muted);
        }

        .form-group input:-webkit-autofill,
        .form-group input:-webkit-autofill:hover,
        .form-group input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px rgba(0, 212, 255, 0.05) inset !important;
            -webkit-text-fill-color: var(--text-light) !important;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--secondary), #00a8d8);
            border: none;
            color: var(--primary);
            font-weight: 600;
            font-size: 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 212, 255, 0.3);
        }

        .login-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .demo-accounts {
            background: rgba(0, 212, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .demo-accounts p {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .demo-account {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid var(--border-color);
            font-size: 12px;
        }

        .demo-account:last-child {
            border-bottom: none;
        }

        .demo-account .role {
            background: rgba(0, 201, 167, 0.15);
            color: #00c9a7;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
        }

        .error-message {
            background: rgba(255, 71, 87, 0.1);
            border: 1px solid rgba(255, 71, 87, 0.3);
            color: #ff4757;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
            animation: slideDown 0.3s ease;
        }

        .error-message.show {
            display: block;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .success-message {
            background: rgba(0, 201, 167, 0.1);
            border: 1px solid rgba(0, 201, 167, 0.3);
            color: #00c9a7;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
            animation: slideDown 0.3s ease;
        }

        .success-message.show {
            display: block;
        }

        .loading-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(0, 51, 102, 0.3);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .loading-spinner.show {
            display: block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .login-footer {
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
        }

        .login-footer a {
            color: var(--secondary);
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 30px 20px;
            }

            .login-title {
                font-size: 24px;
            }

            .login-logo {
                width: 70px;
                height: 70px;
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo"><i class="fas fa-leaf"></i></div>
                <h1 class="login-title">WowoClean</h1>
                <p class="login-subtitle">Sistem Manajemen Limbah B3</p>
            </div>

            <div id="errorMessage" class="error-message"></div>
            <div id="successMessage" class="success-message"></div>

            <form id="loginForm" onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="login-btn" id="loginBtn">
                    <span class="loading-spinner" id="spinner"></span>
                    <span id="btnText">Login</span>
                </button>
            </form>

            <div class="demo-accounts">
                <p><i class="fas fa-info-circle"></i> Akun Demo</p>
                <div class="demo-account">
                    <div>
                        <strong>admin@wowoclean.com</strong><br>
                        <span style="color: var(--text-muted);">password123</span>
                    </div>
                    <div class="role">Admin</div>
                </div>
                <div class="demo-account">
                    <div>
                        <strong>operator@wowoclean.com</strong><br>
                        <span style="color: var(--text-muted);">password123</span>
                    </div>
                    <div class="role">User</div>
                </div>
            </div>

            <div class="login-footer">
                <p>
                    Dokumentasi API: <a href="/api/documentation" target="_blank">Swagger Docs</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/axios.min.js"></script>
    <script>
        const API_BASE_URL = '/api/v1';

        function handleLogin(event) {
            event.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorDiv = document.getElementById('errorMessage');
            const successDiv = document.getElementById('successMessage');
            const btn = document.getElementById('loginBtn');
            const spinner = document.getElementById('spinner');
            const btnText = document.getElementById('btnText');

            // Reset messages
            errorDiv.classList.remove('show');
            successDiv.classList.remove('show');

            // Show loading
            btn.disabled = true;
            spinner.classList.add('show');
            btnText.textContent = 'Memproses...';

            axios.post(`${API_BASE_URL}/login`, {
                email: email,
                password: password
            })
            .then(response => {
                const data = response.data;
                
                // Save token
                localStorage.setItem('auth_token', data.token);
                localStorage.setItem('current_user', JSON.stringify(data.user));

                // Show success message
                successDiv.textContent = '✓ Login berhasil! Silakan tunggu...';
                successDiv.classList.add('show');

                // Redirect to dashboard
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 1000);
            })
            .catch(error => {
                btn.disabled = false;
                spinner.classList.remove('show');
                btnText.textContent = 'Login';

                const errorMsg = error.response?.data?.message || 'Terjadi kesalahan pada server';
                errorDiv.textContent = '✗ ' + errorMsg;
                errorDiv.classList.add('show');
            });
        }

        // Set demo credentials on input click
        document.getElementById('email').addEventListener('focus', function() {
            const parent = this.closest('.form-group');
            if (!parent.querySelector('.demo-hint')) {
                const hint = document.createElement('small');
                hint.className = 'demo-hint';
                hint.style.color = 'var(--text-muted)';
                hint.style.marginTop = '5px';
                hint.textContent = 'Gunakan: admin@wowoclean.com atau operator@wowoclean.com';
                parent.appendChild(hint);
            }
        });
    </script>
</body>
</html>
