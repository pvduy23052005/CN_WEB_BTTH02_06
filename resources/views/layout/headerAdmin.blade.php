
<header class="header">
  <h1>
    Quản trị hệ thống
  </h1>
  <div class="user-info">
    @auth
      <span class="user-name">{{ Auth::user()->username }}</span>
    @endauth
    <div class="user-avatar">A</div>
  </div>
</header>