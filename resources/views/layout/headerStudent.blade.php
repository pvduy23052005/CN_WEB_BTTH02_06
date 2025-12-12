<header class="edu-header">

    <div class="edu-logo">
        <i class="fa-solid fa-book"></i>
        <span>Xin chào</span> 
    </div>
    
    <nav class="edu-nav">
        
        @auth
            {{-- TRƯỜNG HỢP 1: ĐÃ ĐĂNG NHẬP (Hiển thị Dashboard và Khóa học của tôi bằng ICON) --}}
            
            {{-- Dashboard --}}
            <a href="{{ url('/student') }}" class="menu-item-icon me-3" title="Dashboard">
                <i class="fas fa-tachometer-alt fa-lg"></i>
                <span>Khóa học</span>
            </a>
            
            {{-- Khóa học của tôi --}}
            <a href="{{ route('student.home') }}" class="menu-item-icon me-3" title="Khóa học của tôi">
                <i class="fas fa-book-open fa-lg"></i>
                <p>Khóa học của tôi</p>
            </a>
            
            {{-- Thông báo --}}
            <div class="notify me-3">
                <i class="fa-regular fa-bell fa-lg"></i>
                <span class="badge">3</span>
            </div>

            {{-- Tài khoản với Dropdown --}}
            <div class="account-wrapper">
                <div class="account">
                    <img id="avatarPreview" src="{{ asset('default-avatar.png') }}" alt="Avatar">
                    <span>{{ Auth::user()->name ?? 'Tài khoản' }}</span>
                    <i class="fa-solid fa-chevron-down"></i>
                    <input type="file" id="avatarUpload" accept="image/*" hidden>
                </div>
                
                {{-- Dropdown Menu --}}
                <div class="account-dropdown">
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        <span>Hồ sơ của tôi</span>
                    </a>
                    <a href="" class="dropdown-item">
                        <i class="fas fa-cog"></i>
                        <span>Cài đặt</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('auth.logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item logout-btn">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </div>
            
        @else
            {{-- TRƯỜNG HỢP 2: CHƯA ĐĂNG NHẬP (KHÁCH) --}}
            
            {{-- Khám phá Khóa học (Dùng icon + text cho dễ hiểu) --}}
            <a href="{{ route('student.courses.index') }}" class="menu-item me-3">
                <i class="fas fa-search"></i> Khám phá Khóa học
            </a>
            
            {{-- Đăng nhập/Đăng ký --}}
            <a href="{{ url('/auth/login') }}" class="btn btn-sm btn-outline-light me-2">
                 <i class="fas fa-sign-in-alt"></i> Đăng nhập
            </a>
            <a href="{{ url('/auth/register') }}" class="btn btn-sm btn-primary">
                 Đăng ký
            </a>
        @endauth

    </nav>
</header>