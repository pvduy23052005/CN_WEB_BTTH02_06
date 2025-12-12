@extends('layout.layoutAdmin')

@section('main-content')
<div class="main-content">

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