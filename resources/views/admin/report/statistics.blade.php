@extends('layout.layoutAdmin')

@section('main-content')
<div class="main-content">
  <style>
    :root {
      --primary: #4f46e5;
      --bg-card: #ffffff;
      --text-main: #1e293b;
      --text-sub: #64748b;
    }

    /* 1. Overview Cards */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px;
      margin-bottom: 32px;
    }

    .stat-card {
      background: var(--bg-card);
      padding: 24px;
      border-radius: 16px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }

    .stat-label { font-size: 14px; color: var(--text-sub); margin-bottom: 8px; font-weight: 500;}
    .stat-value { font-size: 32px; font-weight: 800; color: var(--text-main); }
    .stat-desc { font-size: 13px; color: #10b981; margin-top: 4px; display: flex; align-items: center; gap:4px; }

    /* 2. Detail Sections */
    .report-split {
      display: grid;
      grid-template-columns: 2fr 1fr; /* Bên trái rộng gấp đôi bên phải */
      gap: 24px;
    }

    .report-card {
      background: var(--bg-card);
      border-radius: 16px;
      border: 1px solid #e2e8f0;
      padding: 24px;
      height: 100%;
    }

    .card-title {
      font-size: 18px;
      font-weight: 700;
      color: var(--text-main);
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 1px solid #f1f5f9;
    }

    /* Top Course List */
    .top-course-item {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 12px 0;
      border-bottom: 1px solid #f8fafc;
    }
    .top-course-item:last-child { border-bottom: none; }
    
    .rank-circle {
      width: 32px; height: 32px;
      background: #f1f5f9; color: #64748b;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; font-size: 14px;
    }
    .top-1 { background: #fee2e2; color: #ef4444; } /* Màu đỏ cho top 1 */
    .top-2 { background: #ffedd5; color: #f97316; } /* Màu cam cho top 2 */
    .top-3 { background: #fef9c3; color: #eab308; } /* Màu vàng cho top 3 */

    .course-info { flex: 1; }
    .course-name { font-weight: 600; color: var(--text-main); display: block; }
    .course-sales { font-size: 13px; color: var(--text-sub); }
    .course-price { font-weight: 700; color: var(--primary); }

    /* Category Progress Bars */
    .cat-item { margin-bottom: 16px; }
    .cat-header { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 6px; font-weight: 500;}
    .progress-bg { width: 100%; height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden; }
    .progress-fill { height: 100%; background: var(--primary); border-radius: 4px; }
  </style>

  <h2 style="margin-bottom: 24px; font-weight: 800;">Báo cáo kinh doanh</h2>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Tổng doanh thu ước tính</div>
      <div class="stat-value">{{ number_format($totalRevenue, 0, ',', '.') }} đ</div>
      <div class="stat-desc">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
        Tích lũy từ trước đến nay
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-label">Tổng số học viên</div>
      <div class="stat-value">{{ number_format($totalStudents) }}</div>
      <div class="stat-desc" style="color: #6366f1;">
        Người dùng hệ thống
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-label">Đăng ký khóa học (Tháng này)</div>
      <div class="stat-value">{{ $newEnrollments }}</div>
      <div class="stat-desc">
        Lượt đăng ký mới
      </div>
    </div>
  </div>

  <div class="report-split">
    
    <div class="report-card">
      <div class="card-title">Top khóa học bán chạy nhất</div>
      
      @foreach($topCourses as $index => $course)
      <div class="top-course-item">
        <div class="rank-circle {{ $index == 0 ? 'top-1' : ($index == 1 ? 'top-2' : ($index == 2 ? 'top-3' : '')) }}">
          {{ $index + 1 }}
        </div>
        
        <div class="course-info">
          <span class="course-name">{{ Str::limit($course->title, 50) }}</span>
          <span class="course-sales">{{ $course->enrollments_count }} học viên đã đăng ký</span>
        </div>
        
        <div class="course-price">
          {{ number_format($course->price, 0, ',', '.') }} đ
        </div>
      </div>
      @endforeach

      @if($topCourses->isEmpty())
        <p style="text-align:center; color:#94a3b8; padding:20px;">Chưa có dữ liệu khóa học.</p>
      @endif
    </div>

    <div class="report-card">
      <div class="card-title">Phân bổ khóa học theo danh mục</div>
      
      @foreach($categoriesStats as $cat)
        @php
           // Tính phần trăm để vẽ thanh progress
           $total = $categoriesStats->sum('courses_count');
           $percent = $total > 0 ? ($cat->courses_count / $total) * 100 : 0;
        @endphp
        <div class="cat-item">
          <div class="cat-header">
            <span>{{ $cat->name }}</span>
            <span style="color: #64748b;">{{ $cat->courses_count }} khóa</span>
          </div>
          <div class="progress-bg">
            <div class="progress-fill" style="width: {{ $percent }}%;"></div>
          </div>
        </div>
      @endforeach
    </div>

  </div>
</div>
@endsection