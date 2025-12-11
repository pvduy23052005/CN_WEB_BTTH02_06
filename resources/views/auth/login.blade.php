<!DOCTYPE html>
<html lang="vi">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng Nhập</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    :root {
      --color-text-primary: #1f2936;
      --color-text-placeholder: #798eae;
      --color-bg-primary: #f9fafb;
      --color-bg-secondary: #ececfd;
      --color-bg-sidebar: #ffffff;
      --color-border-hr: #e2e8f0;
      --color-hover-primary: #695cfe;
      --color-hover-secondary: #e2e2fb;
      --color-shadow: rgba(0, 0, 0, 0.05);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
      background: linear-gradient(135deg, var(--color-bg-secondary) 0%, var(--color-bg-primary) 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-container {
      width: 100%;
      max-width: 440px;
    }

    .login-card {
      background-color: var(--color-bg-sidebar);
      border-radius: 16px;
      box-shadow: 0 10px 40px var(--color-shadow);
      padding: 48px 40px;
      animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .logo-container {
      text-align: center;
      margin-bottom: 32px;
    }

    .logo {
      width: 64px;
      height: 64px;
      background: linear-gradient(135deg, var(--color-hover-primary) 0%, #8b7fff 100%);
      border-radius: 16px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 16px;
      box-shadow: 0 4px 16px rgba(105, 92, 254, 0.3);
    }

    .logo svg {
      width: 32px;
      height: 32px;
      color: white;
    }

    .login-title {
      font-size: 28px;
      font-weight: 700;
      color: var(--color-text-primary);
      margin-bottom: 8px;
    }

    .login-subtitle {
      font-size: 14px;
      color: var(--color-text-placeholder);
    }

    .form-group {
      margin-bottom: 24px;
    }

    .form-label {
      display: block;
      font-size: 14px;
      font-weight: 600;
      color: var(--color-text-primary);
      margin-bottom: 8px;
    }

    .input-wrapper {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      width: 20px;
      height: 20px;
      color: var(--color-text-placeholder);
      pointer-events: none;
    }

    .form-input {
      width: 100%;
      padding: 14px 16px 14px 48px;
      border: 2px solid var(--color-border-hr);
      border-radius: 10px;
      font-size: 15px;
      color: var(--color-text-primary);
      background-color: var(--color-bg-primary);
      transition: all 0.3s ease;
    }

    .form-input:focus {
      outline: none;
      border-color: var(--color-hover-primary);
      background-color: var(--color-bg-sidebar);
      box-shadow: 0 0 0 4px rgba(105, 92, 254, 0.1);
    }

    .form-input::placeholder {
      color: var(--color-text-placeholder);
    }

    .password-toggle {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      color: var(--color-text-placeholder);
      padding: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: color 0.3s ease;
    }

    .password-toggle:hover {
      color: var(--color-hover-primary);
    }

    .password-toggle svg {
      width: 20px;
      height: 20px;
    }

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
    }

    .checkbox-wrapper {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .checkbox-wrapper input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: var(--color-hover-primary);
    }

    .checkbox-wrapper label {
      font-size: 14px;
      color: var(--color-text-primary);
      cursor: pointer;
      user-select: none;
    }

    .forgot-password {
      font-size: 14px;
      color: var(--color-hover-primary);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .forgot-password:hover {
      color: #5648e8;
    }

    .btn-login {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, var(--color-hover-primary) 0%, #8b7fff 100%);
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 16px rgba(105, 92, 254, 0.3);
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 24px rgba(105, 92, 254, 0.4);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 28px 0;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid var(--color-border-hr);
    }

    .divider span {
      padding: 0 16px;
      font-size: 14px;
      color: var(--color-text-placeholder);
    }

    .social-login {
      display: flex;
      gap: 12px;
    }

    .btn-social {
      flex: 1;
      padding: 12px;
      border: 2px solid var(--color-border-hr);
      border-radius: 10px;
      background-color: var(--color-bg-sidebar);
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      font-size: 14px;
      font-weight: 600;
      color: var(--color-text-primary);
    }

    .btn-social:hover {
      border-color: var(--color-hover-primary);
      background-color: var(--color-hover-secondary);
    }

    .btn-social svg {
      width: 20px;
      height: 20px;
    }

    .signup-link {
      text-align: center;
      margin-top: 24px;
      font-size: 14px;
      color: var(--color-text-placeholder);
    }

    .signup-link a {
      color: var(--color-hover-primary);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .signup-link a:hover {
      color: #5648e8;
    }

    .error-message {
      background-color: #fee2e2;
      border: 1px solid #fecaca;
      color: #991b1b;
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 14px;
      margin-bottom: 20px;
      display: none;
    }

    .error-message.show {
      display: block;
      animation: shake 0.4s ease;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-8px); }
      75% { transform: translateX(8px); }
    }

    @media (max-width: 480px) {
      .login-card {
        padding: 32px 24px;
      }

      .login-title {
        font-size: 24px;
      }

      .social-login {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <!-- Logo & Title -->
      <div class="logo-container">
        <div class="logo">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </div>
        <h1 class="login-title">Chào mừng trở lại</h1>
        <p class="login-subtitle">Đăng nhập</p>
      </div>

      <!-- Error Message -->
      <div class="error-message" id="errorMessage">
        Email hoặc mật khẩu không đúng!
      </div>

      <!-- Login Form -->
      <form id="loginForm"
        action ="/auth/login"
        method = "POST"
        >
        @csrf
        {{-- email --}}
        <div class="form-group">
          <label class="form-label" for="email">Email</label>
          <div class="input-wrapper">
            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
            </svg>
            <input 
              type="email" 
              id="email" 
              name="email"
              class="form-input" 
              placeholder="admin@example.com"
              value=""
              required
            >
          </div>
        </div>

        <!-- Password -->
        <div class="form-group">
          <label class="form-label" for="password">Mật khẩu</label>
          <div class="input-wrapper">
            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <input 
              type="password" 
              id="password" 
              name="password"
              class="form-input" 
              placeholder="••••••••"
              required
            >
            <button type="button" class="password-toggle" id="togglePassword">
              <svg id="eyeIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Login Button -->
        <button type="submit" class="btn-login">Đăng nhập</button>
      </form>

      <!-- Signup Link -->
      <div class="signup-link">
        Chưa có tài khoản? <a href="/auth/register">Đăng ký ngay</a>
      </div>
    </div>
  </div>

  <script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function() {
      const type = passwordInput.type === 'password' ? 'text' : 'password';
      passwordInput.type = type;
      
      if (type === 'text') {
        eyeIcon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
        `;
      } else {
        eyeIcon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        `;
      }
    });

  </script>

</body>
</html>
