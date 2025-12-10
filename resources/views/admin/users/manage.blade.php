@extends('layout.layoutAdmin')

@section('main-content')
  <style>
    /* Áp dụng các biến màu đã định nghĩa */

    /* Kiểu dáng khối chính */
    .main-content {
        padding: 30px;
    }

    /* Tiêu đề chính */
    .main-content h1 {
        color: var(--color-brand-primary); /* Màu thương hiệu cho tiêu đề chính */
        margin-bottom: 25px;
        font-weight: 600;
    }

    /* Khối Card chứa bảng */
    .card {
        border: none; /* Bỏ viền mặc định của Bootstrap */
        border-radius: 8px; /* Bo góc nhẹ */
        box-shadow: 0 4px 15px var(--color-shadow); /* Áp dụng bóng đổ nhẹ, hiện đại */
    }

    .card-header {
        background-color: var(--color-bg-primary); /* Nền trắng */
        border-bottom: 1px solid var(--color-border-hr); /* Viền phân cách */
        color: var(--color-text-primary);
        font-weight: 700;
        font-size: 16px;
        padding: 15px 20px;
    }

    /* --- Bảng Dữ liệu (Table Styling) --- */

    .table {
        margin-bottom: 0;
        color: var(--color-text-primary);
    }

    /* Header của bảng */
    .table thead th {
        background-color: var(--color-bg-secondary); /* Nền xám nhẹ cho header */
        color: var(--color-text-secondary);
        font-weight: 600;
        border-bottom: 2px solid var(--color-border-hr);
        text-transform: uppercase;
        font-size: 12px;
        padding: 15px 10px;
    }

    /* Body của bảng */
    .table tbody tr {
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: var(--color-hover-secondary); /* Hiệu ứng hover tinh tế */
    }

    .table tbody td {
        border-top: 1px solid var(--color-border-hr);
        padding: 12px 10px;
        vertical-align: middle;
    }

    /* Styling cho Badge (Role) */
    .badge {
        padding: 5px 8px;
        border-radius: 4px;
        font-weight: 700;
        font-size: 11px;
        /* Định nghĩa màu badge dựa trên biến brand primary */
    }

    /* Ví dụ định nghĩa màu Badge cụ thể (nếu chưa có sẵn trong Bootstrap) */
    .badge.bg-primary {
        background-color: var(--color-brand-primary) !important;
        color: var(--color-brand-contrast) !important;
    }

    .badge.bg-info {
        background-color: #38B2AC !important; /* Ví dụ màu Teal */
        color: white !important;
    }

    .badge.bg-secondary {
        background-color: #A0AEC0 !important; /* Ví dụ màu Gray */
        color: white !important;
    }
  </style>

  <div class="main-content">
    
    <h1 class="h3 mb-4 text-gray-800">Danh sách Người dùng</h1>

    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Thông tin Người dùng</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Tên đầy đủ</th>
                <th>Role</th>
                <th>Ngày tạo</th>
              </tr>
            </thead>
            <tbody>
              @if (isset($users) && count($users) > 0)
                @foreach ($users as $user)
                  <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->fullname }}</td>
                    <td>
                      @if ($user->role == 1)
                        <span class="badge bg-primary">Instructor</span>
                      @elseif ($user->role == 2)
                        <span class="badge bg-info ">Admin</span>
                      @else
                        <span class="badge bg-secondary ">student</span>
                      @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="6" class="text-center">Không có người dùng nào được tìm thấy.</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
@endsection