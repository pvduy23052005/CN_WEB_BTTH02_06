@extends('layout.layoutAdmin')
@section('main-content')
<div class="main-content">
  <style>
    /* Tổng thể */
    :root {
      --primary-color: #4f46e5; /* Indigo */
      --primary-hover: #4338ca;
      --danger-color: #ef4444;
      --danger-hover: #dc2626;
      --text-main: #111827;
      --text-sub: #6b7280;
      --bg-card: #ffffff;
      --bg-body: #f3f4f6;
    }

    .categories-card {
      margin: 24px;
      background: var(--bg-card);
      border-radius: 16px; /* Bo tròn nhiều hơn */
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); /* Soft shadow */
      overflow: hidden; /* Để bo tròn cả table */
      border: 1px solid #e5e7eb;
    }

    /* Header của Card */
    .card-header {
      padding: 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #f3f4f6;
      background: #fff;
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

    /* Button styles */
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
      transition: all 0.2s ease;
      box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
    }

    .btn-create:hover {
      background: var(--primary-hover);
      transform: translateY(-1px);
      box-shadow: 0 6px 10px rgba(79, 70, 229, 0.3);
    }

    /* Table styles */
    .table-container {
      width: 100%;
      overflow-x: auto; /* Responsive cho mobile */
    }

    .categories-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    .categories-table th {
      background: #f9fafb;
      padding: 16px 24px;
      text-align: left;
      font-weight: 600;
      color: var(--text-sub);
      text-transform: uppercase;
      font-size: 12px;
      letter-spacing: 0.05em;
      border-bottom: 1px solid #e5e7eb;
    }

    .categories-table td {
      padding: 16px 24px;
      color: var(--text-main);
      border-bottom: 1px solid #f3f4f6;
      vertical-align: middle;
    }

    .categories-table tbody tr:last-child td {
      border-bottom: none;
    }

    .categories-table tbody tr:hover {
      background-color: #f9fafb; /* Hover nhẹ nhàng */
    }

    /* Cột STT đẹp hơn */
    .stt-badge {
      display: inline-block;
      width: 28px;
      height: 28px;
      line-height: 28px;
      text-align: center;
      background: #eef2ff;
      color: var(--primary-color);
      border-radius: 50%;
      font-weight: 600;
      font-size: 12px;
    }

    /* Actions buttons */
    .actions {
      display: flex;
      gap: 10px;
    }

    .btn-action {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      transition: all 0.2s;
      text-decoration: none;
    }

    /* Edit Button */
    .btn-edit {
      background: #e0e7ff;
      color: var(--primary-color);
    }

    .btn-edit:hover {
      background: var(--primary-color);
      color: #fff;
    }

    /* Delete Button */
    .btn-delete {
      background: #fee2e2;
      color: var(--danger-color);
    }

    .btn-delete:hover {
      background: var(--danger-color);
      color: #fff;
    }

    /* Empty state */
    .categories-empty {
      padding: 40px;
      text-align: center;
      color: var(--text-sub);
    }
    .empty-icon {
      width: 48px;
      height: 48px;
      margin-bottom: 10px;
      color: #d1d5db;
    }

    /* SVG Icons style */
    .icon {
      width: 18px;
      height: 18px;
    }
    .icon-sm {
      width: 16px;
      height: 16px;
    }
  </style>

  <div class="categories-card">
    
    <div class="card-header">
      <h2>
        <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        Danh mục khoa học
      </h2>
      
      <a class="btn-create" href="/admin/category/create">
        <svg class="icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tạo danh mục
      </a>
    </div>

    <div class="table-container">
      <table class="categories-table">
        <thead>
          <tr>
            <th style="width: 80px;">STT</th>
            <th style="width: 30%;">Tên danh mục</th>
            <th>Mô tả</th>
            <th style="width: 120px;">Thao tác</th>
          </tr>
        </thead>
        <tbody>
        @if($categories->isEmpty())
            <tr>
              <td colspan="4" class="categories-empty">
                <svg class="empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p>Chưa có danh mục nào được tạo.</p>
              </td>
            </tr>
        @else
          @foreach($categories as $index => $category)
              <tr>
                <td><span class="stt-badge">{{ $index + 1 }}</span></td>
                <td style="font-weight: 500;">{{ $category->name }}</td>
                <td style="color: #6b7280;">{{ Str::limit($category->description ?? 'Chưa có mô tả', 50) }}</td>
                <td>
                  <div class="actions">
                    <a class="btn-action btn-edit" href="/admin/category/edit/{{$category->id}}" title="Sửa">
                      <svg class="icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </a>

                    <form action="/admin/category/delete/{{$category->id}}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                      @csrf
                      <button type="submit" class="btn-action btn-delete" title="Xóa">
                        <svg class="icon-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
          @endforeach
        @endif
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection