@extends('layout.layoutAdmin')

@section('main-content')
<div class="main-content">
  <style>
    /* Sử dụng lại biến màu từ trang danh sách để đồng bộ */
    :root {
      --primary-color: #4f46e5;
      --primary-hover: #4338ca;
      --text-main: #111827;
      --text-sub: #6b7280;
      --border-color: #d1d5db;
      --bg-card: #ffffff;
      --danger-color: #ef4444;
    }

    /* Căn giữa form cho đẹp mắt */
    .form-container {
      max-width: 600px;
      margin: 40px auto;
    }

    .form-card {
      background: var(--bg-card);
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
      border: 1px solid #f3f4f6;
    }

    /* Header */
    .form-header {
      text-align: center;
      margin-bottom: 32px;
    }

    .form-header h2 {
      margin: 0;
      font-size: 24px;
      font-weight: 800;
      color: var(--text-main);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .form-header p {
      margin-top: 8px;
      color: var(--text-sub);
      font-size: 14px;
    }

    /* Form Elements */
    .form-group {
      margin-bottom: 24px;
    }

    .form-label {
      display: block;
      font-weight: 600;
      font-size: 14px;
      color: #374151;
      margin-bottom: 8px;
    }

    .required-star {
      color: var(--danger-color);
    }

    .form-control {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid var(--border-color);
      border-radius: 10px;
      font-size: 15px;
      color: var(--text-main);
      transition: all 0.2s ease;
      background-color: #f9fafb;
    }

    /* Hiệu ứng khi bấm vào input */
    .form-control:focus {
      outline: none;
      background-color: #fff;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    textarea.form-control {
      resize: vertical; /* Chỉ cho phép kéo giãn chiều dọc */
      min-height: 100px;
    }

    /* Hiển thị lỗi validation */
    .is-invalid {
      border-color: var(--danger-color);
      background-color: #fef2f2;
    }
    
    .error-message {
      color: var(--danger-color);
      font-size: 13px;
      margin-top: 6px;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    /* Buttons */
    .form-actions {
      display: flex;
      gap: 16px;
      margin-top: 32px;
    }

    .btn {
      flex: 1;
      padding: 12px;
      border-radius: 10px;
      font-weight: 600;
      font-size: 15px;
      cursor: pointer;
      text-align: center;
      transition: all 0.2s;
      border: none;
      text-decoration: none;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
    }

    .btn-primary {
      background: var(--primary-color);
      color: #fff;
      box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
    }

    .btn-primary:hover {
      background: var(--primary-hover);
      transform: translateY(-1px);
      box-shadow: 0 6px 12px rgba(79, 70, 229, 0.3);
    }

    .btn-secondary {
      background: #f3f4f6;
      color: #4b5563;
      border: 1px solid #e5e7eb;
    }

    .btn-secondary:hover {
      background: #e5e7eb;
      color: #1f2937;
    }

    /* Icon styles */
    .icon { width: 20px; height: 20px; }
  </style>

  <div class="form-container">
    <div class="form-card">
      
      <div class="form-header">
        <h2>
          <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
          Chỉnh sửa danh mục
        </h2>
        <p>Cập nhật thông tin cho danh mục ID: #{{ $category->id }}</p>
      </div>

      <form method="POST" action="/admin/category/edit/{{ $category->id }}">
        @csrf
        
        <div class="form-group">
          <label class="form-label" for="name">Tên danh mục <span class="required-star">*</span></label>
          <input 
            class="form-control @error('name') is-invalid @enderror" 
            type="text" 
            id="name" 
            name="name" 
            value="{{ old('name', $category->name) }}" 
            placeholder="Nhập tên danh mục..."
            required
          >
          @error('name')
            <div class="error-message">
              <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="form-group">
          <label class="form-label" for="description">Mô tả chi tiết</label>
          <textarea 
            class="form-control" 
            id="description" 
            name="description" 
            rows="4" 
            placeholder="Mô tả ngắn gọn về nội dung của danh mục này..."
          >{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="form-actions">
          <a href="/admin/category" class="btn btn-secondary">
            Hủy bỏ
          </a>
          
          <button class="btn btn-primary" type="submit">
            <svg class="icon" style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Lưu thay đổi
          </button>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection