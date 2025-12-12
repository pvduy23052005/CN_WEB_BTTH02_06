@extends('layout.layoutAdmin')

@section('main-content')
  <div class="main-content">
    <!-- Header -->
    <div class="page-header">
      <h1 class="page-title">Quản lý người dùng</h1>
    </div>

    <!-- Stats Cards -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-label">Tổng người dùng</div>
        <div class="stat-value">{{ isset($users) ? count($users) : 0 }}</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Admin</div>
        <div class="stat-value">{{ isset($users) ? $users->where('role', 2)->count() : 0 }}</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Instructor</div>
        <div class="stat-value">{{ isset($users) ? $users->where('role', 1)->count() : 0 }}</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Student</div>
        <div class="stat-value">{{ isset($users) ? $users->where('role', 0)->count() : 0 }}</div>
      </div>
    </div>

    <!-- Users Table Card -->
    <div class="users-card">
      <div class="card-header">
        <h2 class="card-title">Danh sách người dùng</h2>
        <div class="search-box">
          <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input type="text" id="searchInput" placeholder="Tìm kiếm người dùng..." onkeyup="searchUsers()">
        </div>
      </div>

      <div class="table-wrapper">
        @if (isset($users) && count($users) > 0)
          <table class="users-table" id="usersTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Người dùng</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Ngày tạo</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
                <tr>
                  <td>#{{ $user->id }}</td>
                  <td>
                    <div class="user-info">
                      <div class="user-avatar">
                        {{ strtoupper(substr($user->fullname, 0, 1)) }}
                      </div>
                      <div class="user-details">
                        <div class="user-name">{{ $user->fullname }}</div>
                        <div class="user-username">{{ $user->username }}</div>
                      </div>
                    </div>
                  </td>
                  <td>{{ $user->email }}</td>
                  <td>
                    @if ($user->role == 2)
                      <span class="role-badge role-admin">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Admin
                      </span>
                    @elseif ($user->role == 1)
                      <span class="role-badge role-instructor">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Instructor
                      </span>
                    @else
                      <span class="role-badge role-student">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Student
                      </span>
                    @endif
                  </td>
                  <td>
                    <span class="date-badge">
                      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                      {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}
                    </span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h3>Chưa có người dùng</h3>
            <p>Danh sách người dùng đang trống</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  <script>
    // Tìm kiếm người dùng
    function searchUsers() {
      const input = document.getElementById('searchInput');
      const filter = input.value.toLowerCase();
      const table = document.getElementById('usersTable');
      const rows = table.getElementsByTagName('tr');

      for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;

        for (let j = 0; j < cells.length; j++) {
          const cell = cells[j];
          if (cell) {
            const text = cell.textContent || cell.innerText;
            if (text.toLowerCase().indexOf(filter) > -1) {
              found = true;
              break;
            }
          }
        }

        rows[i].style.display = found ? '' : 'none';
      }
    }
  </script>
@endsection