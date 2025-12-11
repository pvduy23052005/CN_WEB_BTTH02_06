<!DOCTYPE html>
<html lang="vi">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng Ký - Admin Panel</title>
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

    .register-container {
      width: 100%;
      max-width: 480px;
    }

    .register-card {
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

    .register-title {
      font-size: 28px;
      font-weight: 700;
      color: var(--color-text-primary);
      margin-bottom: 8px;
    }

    .register-subtitle {
      font-size: 14px;
      color: var(--color-text-placeholder);
    }

    /* Role Selection */
    .role-selection {
      margin-bottom: 28px;
    }

    .role-buttons {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
      margin-top: 12px;
    }

    .role-btn {
      padding: 16px 12px;
      border: 2px solid var(--color-border-hr);
      border-radius: 12px;
      background-color: var(--color-bg-sidebar);
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      font-weight: 600;
      color: var(--color-text-primary);
    }

    .role-btn:hover {
      border-color: var(--color-hover-primary);
      background-color: var(--color-hover-secondary);
      transform: translateY(-2px);
    }

    .role-btn.active {
      border-color: var(--color-hover-primary);
      background: linear-gradient(135deg, var(--color-hover-primary) 0%, #8b7fff 100%);
      color: white;
      box-shadow: 0 4px 12px rgba(105, 92, 254, 0.3);
    }

    .role-btn svg {
      width: 24px;
      height: 24px;
    }

    .form-group {
      margin-bottom: 20px;
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

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    .btn-register {
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
      margin-top: 8px;
    }

    .btn-register:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 24px rgba(105, 92, 254, 0.4);
    }

    .btn-register:active {
      transform: translateY(0);
    }

    .login-link {
      text-align: center;
      margin-top: 24px;
      font-size: 14px;
      color: var(--color-text-placeholder);
    }

    .login-link a {
      color: var(--color-hover-primary);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .login-link a:hover {
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

    .success-message {
      background-color: #d1fae5;
      border: 1px solid #a7f3d0;
      color: #065f46;
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 14px;
      margin-bottom: 20px;
      display: none;
    }

    .success-message.show {
      display: block;
      animation: fadeIn 0.4s ease;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-8px); }
      75% { transform: translateX(8px); }
    }

    .terms-checkbox {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      margin-bottom: 24px;
    }

    .terms-checkbox input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: var(--color-hover-primary);
      margin-top: 2px;
      flex-shrink: 0;
    }

    .terms-checkbox label {
      font-size: 14px;
      color: var(--color-text-primary);
      cursor: pointer;
      user-select: none;
      line-height: 1.5;
    }

    .terms-checkbox a {
      color: var(--color-hover-primary);
      text-decoration: none;
      font-weight: 600;
    }

    .terms-checkbox a:hover {
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .register-card {
        padding: 32px 24px;
      }

      .register-title {
        font-size: 24px;
      }

      .role-buttons {
        grid-template-columns: 1fr;
      }

      .form-row {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <div class="register-container">
    <div class="register-card">
      <!-- Logo & Title -->
      <div class="logo-container">
        <div class="logo">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
          </svg>
        </div>
        <h1 class="register-title">Tạo tài khoản mới</h1>
        <p class="register-subtitle">Đăng ký để sử dụng hệ thống</p>
      </div>

      <!-- Error/Success Message -->
      <div class="error-message" id="errorMessage">
        Vui lòng kiểm tra lại thông tin!
      </div>
      <div class="success-message" id="successMessage">
        Đăng ký thành công! Đang chuyển hướng...
      </div>

      <!-- Role Selection -->
      <div class="role-selection">
        <label class="form-label">Chọn vai trò đăng ký</label>
        <div class="role-buttons">
          <button type="button" class="role-btn" data-role="admin">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <span>Admin</span>
          </button>
          <button type="button" class="role-btn" data-role="instructor">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span>Instructor</span>
          </button>
          <button type="button" class="role-btn" data-role="student">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span>Student</span>
          </button>
        </div>
      </div>

      <!-- Register Form -->
      <form id="registerForm">
        <!-- Full Name & Email -->
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="fullname">Họ và tên</label>
            <div class="input-wrapper">
              <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <input 
                type="text" 
                id="fullname" 
                class="form-input" 
                placeholder="Nguyễn Văn A"
                required
              >
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <div class="input-wrapper">
              <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
              </svg>
              <input 
                type="email" 
                id="email" 
                class="form-input" 
                placeholder="email@example.com"
                required
              >
            </div>
          </div>
        </div>

        <!-- Phone & Username -->
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="phone">Số điện thoại</label>
            <div class="input-wrapper">
              <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
              <input 
                type="tel" 
                id="phone" 
                class="form-input" 
                placeholder="0123456789"
                required
              >
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="username">Tên đăng nhập</label>
            <div class="input-wrapper">
              <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <input 
                type="text" 
                id="username" 
                class="form-input" 
                placeholder="username"
                required
              >
            </div>
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

        <!-- Confirm Password -->
        <div class="form-group">
          <label class="form-label" for="confirmPassword">Xác nhận mật khẩu</label>
          <div class="input-wrapper">
            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <input 
              type="password" 
              id="confirmPassword" 
              class="form-input" 
              placeholder="••••••••"
              required
            >
            <button type="button" class="password-toggle" id="toggleConfirmPassword">
              <svg id="eyeIcon2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
        </div>


        <!-- Register Button -->
        <button type="submit" class="btn-register">Đăng ký</button>
      </form>

      <!-- Login Link -->
      <div class="login-link">
        Đã có tài khoản? <a href="/auth/login">Đăng nhập ngay</a>
      </div>
    </div>
  </div>

  <script>
    // Role selection
    let selectedRole = null;
    const roleBtns = document.querySelectorAll('.role-btn');

    roleBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        roleBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        selectedRole = this.dataset.role;
        console.log('Selected role:', selectedRole);
      });
    });

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

    // Toggle confirm password visibility
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const eyeIcon2 = document.getElementById('eyeIcon2');

    toggleConfirmPassword.addEventListener('click', function() {
      const type = confirmPasswordInput.type === 'password' ? 'text' : 'password';
      confirmPasswordInput.type = type;
      
      if (type === 'text') {
        eyeIcon2.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
        `;
      } else {
        eyeIcon2.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        `;
      }
    });

    // Form submission
    const registerForm = document.getElementById('registerForm');
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');

    registerForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Check if role is selected
      if (!selectedRole) {
        errorMessage.textContent = 'Vui lòng chọn vai trò đăng ký!';
        errorMessage.classList.add('show');
        setTimeout(() => {
          errorMessage.classList.remove('show');
        }, 3000);
        return;
      }

      // Get form values
      const fullname = document.getElementById('fullname').value;
      const email = document.getElementById('email').value;
      const phone = document.getElementById('phone').value;
      const username = document.getElementById('username').value;
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      const termsAccepted = document.getElementById('terms').checked;

      // Validation
      if (password !== confirmPassword) {
        errorMessage.textContent = 'Mật khẩu xác nhận không khớp!';
        errorMessage.classList.add('show');
        setTimeout(() => {
          errorMessage.classList.remove('show');
        }, 3000);
        return;
      }

      if (password.length < 6) {
        errorMessage.textContent = 'Mật khẩu phải có ít nhất 6 ký tự!';
        errorMessage.classList.add('show');
        setTimeout(() => {
          errorMessage.classList.remove('show');
        }, 3000);
        return;
      }

      if (!termsAccepted) {
        errorMessage.textContent = 'Vui lòng đồng ý với điều khoản dịch vụ!';
        errorMessage.classList.add('show');
        setTimeout(() => {
          errorMessage.classList.remove('show');
        }, 3000);
        return;
      }

      // Success
      successMessage.classList.add('show');
      console.log('Registration data:', {
        fullname,
        email,
        phone,
        username,
        password,
        role: selectedRole
      });

      // Redirect after 2 seconds
      setTimeout(() => {
        alert(`Đăng ký thành công với vai trò: ${selectedRole}`);
        // window.location.