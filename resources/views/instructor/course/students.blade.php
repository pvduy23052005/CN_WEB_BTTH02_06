@extends('layout.layoutAdmin')

@section('main-content')

<style>
    /* CSS nội bộ để fix giao diện */
    .table-custom {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        border: 1px solid #eef2f6;
        border-radius: 8px;
        overflow: hidden;
    }
    .table-custom th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        padding: 15px;
        text-align: left;
        border-bottom: 2px solid #eef2f6;
    }
    .table-custom td {
        padding: 12px 15px;
        vertical-align: middle !important;
        border-bottom: 1px solid #eef2f6;
        color: #555;
    }
    /* Avatar học viên */
    .student-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        overflow: hidden;
    }
    .avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    /* Thanh tiến độ */
    .progress-bar-bg {
        width: 100px;
        height: 6px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 5px;
    }
    .progress-bar-fill {
        height: 100%;
        background: #198754; /* Màu xanh lá */
        border-radius: 10px;
    }
    .badge-status {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        min-width: 80px;
        text-align: center;
    }
    .status-active { background: #d1e7dd; color: #0f5132; }
    .status-completed { background: #cfe2ff; color: #084298; }
    .status-dropped { background: #f8d7da; color: #842029; }
</style>

<div class="admin-content-wrapper">

    <div class="custom-card" style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        
        {{-- Header --}}
        <div class="card-header-custom" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <a href="{{ route('instructor.courses.index') }}" class="btn-back" style="color: #666; text-decoration: none; border: 1px solid #ddd; padding: 6px 12px; border-radius: 5px; background: #f9f9f9; font-size: 14px;">
                    <i class="fas fa-arrow-left"></i> Khóa học
                </a>
                <div>
                    <h1 class="page-title" style="margin: 0; font-size: 22px; color: #333;">Danh sách học viên</h1>
                    <div style="font-size: 13px; color: #666; margin-top: 2px;">
                        Khóa học: <b style="color: #0d6efd;">{{ $course->title }}</b>
                    </div>
                </div>
            </div>
            
            <div style="font-weight: bold; color: #555;">
                Tổng số: <span style="color: #0d6efd;">{{ $enrollments->count() }}</span> học viên
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">STT</th>
                        <th width="35%">Thông tin học viên</th>
                        <th width="20%">Ngày đăng ký</th>
                        <th width="25%">Tiến độ học tập</th>
                        <th width="15%" class="text-center">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @if($enrollments->count() > 0)
                        @foreach($enrollments as $enroll)
                            @php 
                                $student = $enroll->student;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                
                                <td>
                                    <div class="student-info">
                                        <div class="avatar-circle">
                                            @if($student->avatar)
                                                <img src="{{ asset($student->avatar) }}" alt="Avt">
                                            @else
                                                {{ substr($student->fullname ?? $student->username, 0, 1) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div style="font-weight: 600; color: #333;">{{ $student->fullname ?? 'Chưa cập nhật tên' }}</div>
                                            <div style="font-size: 12px; color: #888;">{{ $student->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($enroll->enrolled_date)->format('d/m/Y H:i') }}
                                </td>

                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <span style="font-weight: bold; font-size: 13px; color: {{ $enroll->progress == 100 ? '#0d6efd' : '#198754' }};">
                                            {{ $enroll->progress }}%
                                        </span>
                                        <div class="progress-bar-bg">
                                            <div class="progress-bar-fill" style="width: {{ $enroll->progress }}%; background-color: {{ $enroll->progress == 100 ? '#0d6efd' : '#198754' }};"></div>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    {{-- LOGIC CHECK TRẠNG THÁI --}}
                                    @if($enroll->progress == 100 || $enroll->status == 'completed')
                                        <span class="badge-status status-completed">Hoàn thành</span>
                                    @elseif($enroll->status == 'active')
                                        <span class="badge-status status-active">Đang học</span>
                                    @else
                                        <span class="badge-status status-dropped">Đã hủy</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-users-slash" style="font-size: 40px; margin-bottom: 10px; display: block; color: #eee;"></i>
                                Chưa có học viên nào đăng ký khóa học này.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection