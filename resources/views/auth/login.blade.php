<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('storage/logo/logo01.png') }}" >
    <title>Login - Pengadilan Negeri Gorontalo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a7f5c;
            --primary-light: #24a57c;
            --primary-dark: #135c43;
            --accent-color: #d4af37;
            --text-color: #2c3e50;
            --light-gray: #f8f9fa;
            --border-color: #e1e8ed;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100" opacity="0.03"><path d="M50,10 L90,30 L90,70 L50,90 L10,70 L10,30 Z" fill="%231a7f5c"/></svg>');
            z-index: -1;
        }
        
        .login-container {
            width: 100%;
            max-width: 440px;
            background-color: white;
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
            position: relative;
            z-index: 1;
        }
        
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: white;
            padding: 40px 25px 30px;
            position: relative;
            overflow: hidden;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100" opacity="0.1"><path d="M50,10 L90,30 L90,70 L50,90 L10,70 L10,30 Z" fill="white"/></svg>');
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
            width: 100%;
        }
        
        .logo-icon {
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 20px;
            transition: var(--transition);
            flex-shrink: 0;
        }
        
        .logo-icon:hover {
            transform: scale(1.05);
        }
        
        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }
        
        .institution-name {
            font-size: 22px;
            font-weight: 600;
            text-align: left;
            line-height: 1.3;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        
        .login-title {
            font-size: 26px;
            font-weight: 500;
            position: relative;
            z-index: 2;
            text-align: center;
            margin-top: 10px;
            width: 100%;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        
        .login-form {
            padding: 35px 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-color);
            transition: var(--transition);
            font-size: 15px;
        }
        
        .input-container {
            position: relative;
        }
        
        .form-input {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 16px;
            transition: var(--transition);
            outline: none;
            background-color: var(--light-gray);
        }
        
        .form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 127, 92, 0.15);
            background-color: white;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 18px;
            z-index: 2;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
        
        .error-message {
            color: var(--error-color);
            font-size: 14px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            opacity: 0;
            transform: translateY(-10px);
            transition: var(--transition);
            height: 0;
            overflow: hidden;
        }
        
        .error-message.show {
            opacity: 1;
            transform: translateY(0);
            height: auto;
        }
        
        .error-icon {
            margin-right: 8px;
            font-size: 16px;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .checkbox {
            margin-right: 8px;
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            cursor: pointer;
        }
        
        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            transition: var(--transition);
            position: relative;
        }
        
        .forgot-password::after {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background-color: var(--primary-color);
            transition: var(--transition);
        }
        
        .forgot-password:hover {
            color: var(--primary-dark);
        }
        
        .forgot-password:hover::after {
            width: 100%;
        }
        
        .login-button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-button::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        
        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 15px rgba(26, 127, 92, 0.3);
        }
        
        .login-button:hover::before {
            left: 100%;
        }
        
        .button-icon {
            margin-right: 10px;
            transition: var(--transition);
        }
        
        .login-button:hover .button-icon {
            transform: translateX(5px);
        }
        
        .session-status {
            padding: 12px 15px;
            background-color: rgba(46, 125, 50, 0.1);
            border-left: 4px solid var(--primary-color);
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition);
        }
        
        .input-success {
            border-color: var(--success-color) !important;
        }
        
        .input-error {
            border-color: var(--error-color) !important;
        }
        
        /* CSS baru untuk layout logo dan judul */
        .header-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            position: relative;
            z-index: 2;
        }
        
        .logo-title-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .logo-main {
            width: 125px;
            height: 125px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 20px;
            flex-shrink: 0;
        }
        
        .logo-main img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 3px 5px rgba(0, 0, 0, 0.3));
        }
        
        @media (max-width: 480px) {
            .login-container {
                max-width: 100%;
            }
            
            .login-form {
                padding: 25px 20px;
            }
            
            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .forgot-password {
                margin-top: 10px;
            }
            
            .logo-main {
                width: 90px;
                height: 90px;
                margin-right: 15px;
            }
            
            .institution-name {
                font-size: 18px;
            }
            
            .login-header {
                min-height: 200px;
                padding: 30px 20px 25px;
            }
            
            .login-title {
                font-size: 22px;
            }
        }
        
        @media (max-width: 360px) {
            .logo-main {
                width: 80px;
                height: 80px;
                margin-right: 12px;
            }
            
            .institution-name {
                font-size: 16px;
            }
            
            .login-title {
                font-size: 20px;
            }
            
            .login-header {
                min-height: 180px;
                padding: 25px 15px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="header-content">
                <div class="logo-title-wrapper">
                    <div class="logo-main">
                        <img src="{{ asset('storage/logo/logo01.png') }}" 
                             alt="Logo PN Gorontalo">
                    </div>
                    <div class="institution-name">Pengadilan Negeri<br>Gorontalo</div>
                </div>
                <div class="login-title">Login</div>
            </div>
        </div>
        
        <form class="login-form" method="POST" action="{{ route('login') }}">
            @csrf
            
            <!-- Email Address -->
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <div class="input-container">
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input class="form-input" id="email" type="email" name="email" value="" required autofocus autocomplete="username" placeholder="Masukkan email Anda">
                </div>
                <div class="error-message" id="email-error">
                    <span class="error-icon"><i class="fas fa-exclamation-circle"></i></span> Email tidak valid
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-container">
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input class="form-input" id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password Anda">
                    <div class="password-toggle" id="password-toggle">
                        <i class="fas fa-eye"></i>
                    </div>
                </div>
                <div class="error-message" id="password-error">
                    <span class="error-icon"><i class="fas fa-exclamation-circle"></i></span> Password salah
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="remember-forgot">
                <div class="remember-me">
                    <input id="remember_me" type="checkbox" class="checkbox" name="remember">
                    <label for="remember_me">Ingat saya</label>
                </div>
                <a class="forgot-password" href="{{ route('password.request') }}">Lupa password?</a>
            </div>

            <!-- Login Button -->
            <button type="submit" class="login-button">
                <span class="button-icon">
                    <i class="fas fa-sign-in-alt"></i>
                </span>
                Login
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formInputs = document.querySelectorAll('.form-input');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            const passwordToggle = document.getElementById('password-toggle');
            const passwordInput = document.getElementById('password');
            
            // Validasi email
            const emailInput = document.getElementById('email');
            emailInput.addEventListener('blur', function() {
                const emailValue = this.value.trim();
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (emailValue === '') {
                    showError(this, emailError, 'Email harus diisi');
                } else if (!emailPattern.test(emailValue)) {
                    showError(this, emailError, 'Format email tidak valid');
                } else {
                    showSuccess(this, emailError);
                }
            });
            
            // Validasi password
            passwordInput.addEventListener('blur', function() {
                const passwordValue = this.value.trim();
                
                if (passwordValue === '') {
                    showError(this, passwordError, 'Password harus diisi');
                } else if (passwordValue.length < 6) {
                    showError(this, passwordError, 'Password minimal 6 karakter');
                } else {
                    showSuccess(this, passwordError);
                }
            });
            
            // Toggle password visibility
            passwordToggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
            
            // Fungsi untuk menampilkan error
            function showError(input, errorElement, message) {
                input.classList.add('input-error');
                input.classList.remove('input-success');
                errorElement.querySelector('span:last-child').textContent = message;
                errorElement.classList.add('show');
            }
            
            // Fungsi untuk menampilkan sukses
            function showSuccess(input, errorElement) {
                input.classList.remove('input-error');
                input.classList.add('input-success');
                errorElement.classList.remove('show');
            }
            
            // Animasi input focus
            formInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.parentElement.querySelector('.form-label').style.color = 'var(--primary-color)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.parentElement.querySelector('.form-label').style.color = 'var(--text-color)';
                });
            });
            
            // Animasi tombol login
            const loginButton = document.querySelector('.login-button');
            loginButton.addEventListener('click', function(e) {
                // Validasi sebelum submit
                let isValid = true;
                
                // Validasi email
                const emailValue = emailInput.value.trim();
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (emailValue === '' || !emailPattern.test(emailValue)) {
                    showError(emailInput, emailError, 'Email tidak valid');
                    isValid = false;
                }
                
                // Validasi password
                const passwordValue = passwordInput.value.trim();
                if (passwordValue === '' || passwordValue.length < 6) {
                    showError(passwordInput, passwordError, 'Password minimal 6 karakter');
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
                
                // Animasi tombol
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });
    </script>
</body>
</html>