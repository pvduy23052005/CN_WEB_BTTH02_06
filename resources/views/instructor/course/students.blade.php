@extends('layout.layoutAdmin')

@section('main-content')

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
                                $student = $enroll->student; // Lấy thông tin user từ quan hệ
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                
                                {{-- Cột thông tin --}}
                                <td>
                                    <div class="student-info">
                                        {{-- Avatar: Nếu có ảnh thì hiện, k có thì hiện chữ cái đầu --}}
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

                                {{-- Ngày đăng ký --}}
                                <td>
                                    {{ \Carbon\Carbon::parse($enroll->enrolled_date)->format('d/m/Y H:i') }}
                                </td>

                                {{-- Tiến độ --}}
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <span style="font-weight: bold; font-size: 13px; color: #198754;">{{ $enroll->progress }}%</span>
                                        <div class="progress-bar-bg">
                                            <div class="progress-bar-fill" style="width: {{ $enroll->progress }}%;"></div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Trạng thái --}}
                                <td class="text-center">
                                    @if($enroll->status == 'active')
                                        <span class="badge-status status-active">Đang học</span>
                                    @elseif($enroll->status == 'completed')
                                        <span class="badge-status status-completed">Hoàn thành</span>
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