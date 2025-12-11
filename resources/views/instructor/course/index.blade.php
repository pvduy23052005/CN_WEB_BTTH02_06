@extends('layout.layoutAdmin')

@section('main-content')

{{-- 1. PHẦN CSS: STYLE "HOA LÁ CÀNH" --}}
<style>
    /* --- KHUNG BAO NGOÀI (LAYOUT) --- */
    .admin-content-wrapper {
        margin-left: 260px; /* Đẩy sang phải tránh Sidebar */
        width: calc(100% - 280px); /* Trừ hao khoảng cách */
        padding: 30px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fc; /* Màu nền xám nhạt toàn trang */
        min-height: 100vh;
    }

    /* --- THẺ CARD CHỨA BẢNG --- */
    .custom-card {
        background: #fff;
        border-radius: 15px; /* Bo góc mềm mại */
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05); /* Bóng đổ nhẹ sang chảnh */
        overflow: hidden; /* Để header không bị lòi ra khỏi border-radius */
        border: none;
    }

    /* --- HEADER CỦA CARD (TIÊU ĐỀ + NÚT) --- */
    .card-header-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 30px;
        background: #fff;
        border-bottom: 1px solid #edf2f9;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        /* Tạo điểm nhấn gạch chân nhỏ dưới tiêu đề */
        position: relative; 
    }
    .page-title::after {
        content: '';
        display: block;
        width: 50px;
        height: 4px;
        background: linear-gradient(90deg, #4e73df, #224abe);
        margin-top: 5px;
        border-radius: 2px;
    }

    /* --- NÚT THÊM MỚI (Gradient) --- */
    .btn-add-new {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 50px; /* Nút hình viên thuốc */
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(118, 75, 162, 0.4);
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-add-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(118, 75, 162, 0.6);
        color: white;
        text-decoration: none;
    }

    /* --- BẢNG DỮ LIỆU --- */
    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }

    .table-custom thead {
        background-color: #f8f9fc;
        color: #8898aa;
        text-transform: uppercase;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .table-custom th {
        padding: 15px 30px;
        text-align: left;
        border-bottom: 1px solid #e3e6f0;
    }

    .table-custom td {
        padding: 20px 30px;
        vertical-align: middle;
        border-bottom: 1px solid #edf2f9;
        color: #525f7f;
        font-size: 0.95rem;
    }

    /* Hiệu ứng khi di chuột vào hàng */
    .table-custom tbody tr:hover {
        background-color: #f6f9fc;
        transform: scale(1.005); /* Phóng to cực nhẹ */
        transition: all 0.2s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        z-index: 1;
        position: relative;
    }

    /* --- ẢNH KHÓA HỌC --- */
    .course-img-wrapper {
        width: 100px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }
    
    .course-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.2s;
    }

    .table-custom tbody tr:hover .course-img-wrapper img {
        transform: scale(1.1); /* Zoom ảnh khi hover vào hàng */
    }

    /* --- TÊN KHÓA HỌC --- */
    .course-name {
        font-weight: 600;
        color: #32325d;
        display: block;
    }

    /* --- GIÁ TIỀN --- */
    .price-tag {
        font-weight: 700;
        color: #e74a3b; /* Màu đỏ nổi bật */
        background: #fff0f0;
        padding: 5px 10px;
        border-radius: 6px;
        display: inline-block;
    }

    /* --- DANH MỤC (BADGE) --- */
    .category-badge {
        background-color: #e0f2ff;
        color: #007bff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* --- NÚT HÀNH ĐỘNG --- */
    .action-btn {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 5px;
        cursor: pointer;
        transition: all 0.2s;
        color: white;
    }

    .btn-edit {
        background-color: #ffc107;
        box-shadow: 0 4px 6px rgba(255, 193, 7, 0.3);
    }
    .btn-edit:hover { background-color: #e0a800; transform: translateY(-2px); }

    .btn-delete {
        background-color: #dc3545;
        box-shadow: 0 4px 6px rgba(220, 53, 69, 0.3);
    }
    .btn-delete:hover { background-color: #c82333; transform: translateY(-2px); }

</style>

{{-- 2. PHẦN HTML --}}
<div class="admin-content-wrapper">
    
    @if(session('msg'))
        <div class="alert alert-success mb-3" style="border-radius: 10px;">{{ session('msg') }}</div>
    @endif

    <div class="custom-card">
        {{-- Header của bảng --}}
        <div class="card-header-custom">
            <h1 class="page-title">Danh sách khóa học</h1>
            
            <button type="button" class="btn-add-new">
                <i class="fas fa-plus"></i>
                <span>Thêm khóa học</span>
            </button>
        </div>

        {{-- Nội dung bảng --}}
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="15%">Ảnh mô tả</th>
                        <th width="30%">Tên khóa học</th>
                        <th width="20%">Danh mục</th>
                        <th width="15%">Giá bán</th>
                        <th width="15%" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                    <tr>
                        <td style="font-weight: bold; color: #8898aa;">#{{ $course->id }}</td>
                        
                        <td>
                            <div class="course-img-wrapper">
                                @if($course->image)
                                    <img src="{{ $course->image }}" alt="img">
                                @else
                                    <div style="width:100%; height:100%; background:#eee; display:flex; align-items:center; justify-content:center; color:#999; font-size:12px;">No Image</div>
                                @endif
                            </div>
                        </td>

                        <td>
                            <span class="course-name">{{ $course->title }}</span>
                        </td>
                        
                        <td>
                            <span class="category-badge">
                                {{ optional($course->category)->name ?? 'Chưa phân loại' }}
                            </span>
                        </td>
                        
                        <td>
                            <span class="price-tag">{{ number_format($course->price) }} đ</span>
                        </td>
                        
                        <td style="text-align: center;">
                            <button class="action-btn btn-edit" title="Sửa">
                                <i class="fas fa-pen" style="font-size: 14px;"></i>
                            </button>
                            
                            <button class="action-btn btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa?')">
                                <i class="fas fa-trash" style="font-size: 14px;"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection