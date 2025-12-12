@extends('layout.layoutAdmin')

@section('main-content')
<div class="main-content">
  <style>
    :root {
      --primary: #4f46e5;
      --secondary: #64748b;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
      --bg-card: #ffffff;
    }

    /* Grid Layout cho các thẻ thống kê */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 24px;
      margin-bottom: 30px;
    }

    /* Style cho từng thẻ Card */
    .stat-card {
      background: var(--bg-card);
      padding: 24px;
      border-radius: 16px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
      display: flex;
      align-items: center;
      gap: 20px;
      transition: transform 0.2s;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    /* Icon trong card */
    .stat-icon {
      width: 56px;
      height: 56px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
    }
    
    .icon-blue { background: #e0e7ff; color: #4f46e5; }
    .icon-green { background: #d1fae5; color: #059669; }
    .icon-orange { background: #ffedd5; color: #c2410c; }
    .icon-purple { background: #f3e8ff; color: #7e22ce; }

    /* Nội dung text trong card */
    .stat-info h3 {
      font-size: 28px;
      font-weight: 700;
      margin: 0;
      color: #1e293b;
    }
    .stat-info p {
      margin: 4px 0 0;
      color: #64748b;
      font-size: 14px;
      font-weight: 500;
    }

    /* Section Tiêu đề */
    .section-header {
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .section-title {
      font-size: 18px;
      font-weight: 700;
      color: #1e293b;
    }

    /* Table đơn giản cho Dashboard */
    .dash-table-card {
      background: white;
      border-radius: 16px;
      padding: 24px;
      border: 1px solid #e2e8f0;
    }
    .dash-table {
      width: 100%;
      border-collapse: collapse;
    }
    .dash-table th { text-align: left; padding: 12px; color: #64748b; font-size: 13px; border-bottom: 1px solid #f1f5f9; }
    .dash-table td { padding: 16px 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #334155; }
    .dash-table tr:last-child td { border-bottom: none; }
    
    .btn-sm {
      padding: 6px 12px;
      font-size: 12px;
      border-radius: 6px;
      text-decoration: none;
      background: #e0e7ff;
      color: #4f46e5;
      font-weight: 600;
    }
    .btn-sm:hover { background: #4f46e5; color: white; }
  </style>

  <div style="margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 800; color: #1e293b; margin: 0;">Dashboard</h2>
    <p style="color: #64748b; margin: 4px 0 0;">Chào mừng quay trở lại, Administrator!</p>
  </div>

  <div class="stats-grid">
    
    <div class="stat-card">
      <div class="stat-icon icon-blue">
        <svg style="width:28px;height:28px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
      </div>
      <div class="stat-info">
        <h3>{{ $totalStudents }}</h3>
        <p>Học viên</p>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon icon-purple">
        <svg style="width:28px;height:28px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
      </div>
      <div class="stat-info">
        <h3>{{ $totalInstructors }}</h3>
        <p>Giảng viên</p>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon icon-green">
        <svg style="width:28px;height:28px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      </div>
      <div class="stat-info">
        <h3>{{ $totalCourses }}</h3>
        <p>Khóa học</p>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon icon-orange">
        <svg style="width:28px;height:28px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <div class="stat-info">
        <h3>{{ $pendingCoursesCount }}</h3>
        <p>Chờ duyệt</p>
      </div>
    </div>
  </div>

  <div class="section-header">
    <div class="section-title">Khóa học chờ duyệt gần đây</div>
    <a href="/admin/courses" style="font-size:14px; color:#4f46e5; text-decoration:none;">Xem tất cả &rarr;</a>
  </div>

  <div class="dash-table-card">
    <table class="dash-table">
      <thead>
        <tr>
          <th>Tên khóa học</th>
          <th>Giảng viên</th>
          <th>Ngày tạo</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @if($pendingCourses->count() > 0)
          @foreach($pendingCourses as $course)
          <tr>
            <td>
              <div style="font-weight: 600;">{{ Str::limit($course->title, 40) }}</div>
              <div style="font-size: 12px; color: #94a3b8;">Danh mục: {{ $course->category->name ?? 'None' }}</div>
            </td>
            <td>{{ $course->instructor->fullname ?? 'Unknown' }}</td>
            <td>{{ $course->created_at ? $course->created_at->format('d/m/Y') : 'N/A' }}</td>
            <td>
              <a href="/admin/courses" class="btn-sm">Kiểm tra</a>
            </td>
          </tr>
          @endforeach
        @else
          <tr>
            <td colspan="4" style="text-align:center; padding: 30px; color: #94a3b8;">
              Không có khóa học nào đang chờ duyệt.
            </td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>

</div>
@endsection