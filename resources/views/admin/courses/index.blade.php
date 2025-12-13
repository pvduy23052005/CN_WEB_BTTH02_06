@extends('layout.layoutAdmin')

@section('main-content')
<div class="main-content">
  <style>
    /* --- CSS Variables --- */
    :root {
      --primary-color: #4f46e5;
      --success-color: #10b981;
      --warning-color: #f59e0b;
      --danger-color: #ef4444;
      --text-main: #111827;
      --text-sub: #6b7280;
      --bg-card: #ffffff;
    }

    /* --- ALERT STYLES (MỚI THÊM) --- */
    .alert {
      padding: 16px;
      margin-bottom: 24px;
      border-radius: 12px;
      font-weight: 500;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 12px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      animation: slideDown 0.4s ease-out;
    }

    /* Màu cho thông báo thành công */
    .alert-success {
      background-color: #ecfdf5; /* Xanh nhạt */
      color: #065f46; /* Chữ xanh đậm */
      border: 1px solid #a7f3d0;
    }

    /* Màu cho thông báo lỗi */
    .alert-error {
      background-color: #fef2f2; /* Đỏ nhạt */
      color: #991b1b; /* Chữ đỏ đậm */
      border: 1px solid #fecaca;
    }

    /* Hiệu ứng trượt xuống */
    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* --- CÁC STYLE CŨ --- */
    .table-card {
      margin-top: 0; /* Reset margin top vì đã có alert ở trên */
      background: var(--bg-card);
      border-radius: 16px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
      border: 1px solid #e5e7eb;
      overflow: hidden;
    }

    .card-header {
      padding: 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #f3f4f6;
    }

    .card-header h2 {
      margin: 0;
      font-size: 22px;
      font-weight: 700;
      color: var(--text-main);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .btn-create {
      padding: 10px 20px;
      border-radius: 8px;
      background: var(--primary-color);
      color: #fff;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
    }
    .btn-create:hover { opacity: 0.9; transform: translateY(-1px); }

    .table-responsive { width: 100%; overflow-x: auto; }
    
    .custom-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    .custom-table th {
      background: #f9fafb;
      padding: 16px;
      text-align: left;
      font-weight: 600;
      color: var(--text-sub);
      text-transform: uppercase;
      font-size: 12px;
      border-bottom: 1px solid #e5e7eb;
    }

    .custom-table td {
      padding: 16px;
      color: var(--text-main);
      border-bottom: 1px solid #f3f4f6;
      vertical-align: middle;
    }

    .course-thumb {
      width: 60px;
      height: 40px;
      object-fit: cover;
      border-radius: 6px;
      background: #e5e7eb;
    }

    .course-info h4 { margin: 0; font-size: 15px; font-weight: 600; }
    .course-info span { font-size: 13px; color: var(--text-sub); }

    .badge {
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-warning { background: #fef3c7; color: #92400e; }

    .actions { display: flex; gap: 8px; }
    .btn-icon {
      width: 34px; height: 34px;
      border-radius: 8px;
      border: none;
      display: flex;
      align-items: center; justify-content: center;
      cursor: pointer;
      transition: all 0.2s;
      text-decoration: none;
    }
    
    .btn-approve { background: #d1fae5; color: #059669; }
    .btn-approve:hover { background: #10b981; color: #fff; }

    .btn-edit { background: #e0e7ff; color: var(--primary-color); }
    .btn-edit:hover { background: var(--primary-color); color: #fff; }

    .btn-delete { background: #fee2e2; color: var(--danger-color); }
    .btn-delete:hover { background: var(--danger-color); color: #fff; }

    .icon { width: 18px; height: 18px; }
  </style>

  @if(session('success'))
    <div class="alert alert-success">
      <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  {{-- 2. Thông báo Lỗi --}}
  @if(session('error'))
    <div class="alert alert-error">
      <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
      </svg>
      <span>{{ session('error') }}</span>
    </div>
  @endif
  <div class="table-card">
    <div class="card-header">
      <h2>
        <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        Quản lý khóa học
      </h2>
    </div>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th width="5%">#</th>
            <th width="10%">Hình ảnh</th>
            <th width="30%">Thông tin khóa học</th>
            <th width="15%">Giá</th>
            <th width="15%">Trạng thái</th>
            <th width="10%">Danh mục</th>
            <th width="15%">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($courses) && count($courses) > 0)
            @foreach($courses as $index => $course)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>
                <img src="{{ $course->image ? asset($course->image) : 'https://placehold.co/60x40?text=No+Img' }}" class="course-thumb" alt="Course Img">
              </td>
              <td>
                <div class="course-info">
                  <h4>{{ Str::limit($course->title, 40) }}</h4>
                  <span>GV: {{ $course->instructor->fullname ?? 'Unknown' }}</span>
                </div>
              </td>
              <td>
                <span style="font-weight:600; color: #4f46e5;">
                  {{ number_format($course->price, 0, ',', '.') }} đ
                </span>
              </td>
              <td>
                @if($course->is_active == 1)
                  <span class="badge badge-success">
                    <svg class="icon" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Đã duyệt
                  </span>
                @else
                  <span class="badge badge-warning">
                    <svg class="icon" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Chờ duyệt
                  </span>
                @endif
              </td>
              <td>
                <span style="font-size:13px; background:#f3f4f6; padding:4px 8px; border-radius:4px;">
                  {{ $course->category->name ?? 'None' }}
                </span>
              </td>
              <td>
                <div class="actions">
                  
                  @if($course->is_active == 0)
                  <form action="/admin/course/approve/{{ $course->id }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-icon btn-approve" title="Duyệt khóa học này">
                      <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </button>
                  </form>
                  @endif

                  <a href="/admin/courses/edit/{{ $course->id }}" class="btn-icon btn-edit" title="Chỉnh sửa">
                    <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </a>

                  <form action="/admin/courses/delete/{{ $course->id }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa?')">
                    @csrf
                    <button type="submit" class="btn-icon btn-delete" title="Xóa">
                      <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </form>

                </div>
              </td>
            </tr>
            @endforeach
          @else
            <tr>
              <td colspan="7" style="text-align:center; padding: 40px; color: #6b7280;">
                Chưa có khóa học nào trong hệ thống.
              </td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>

    @if(isset($courses) && method_exists($courses, 'links'))
      <div style="padding: 20px;">
        {{ $courses->links() }}
      </div>
    @endif
  </div>
</div>
@endsection