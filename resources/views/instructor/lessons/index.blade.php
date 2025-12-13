@extends('layout.layoutAdmin')

@section('main-content')



<div class="admin-content-wrapper">

    {{-- Thông báo --}}
    @if(session('msg'))
        <div class="alert alert-success" style="padding: 15px; margin-bottom: 20px; background: #d1e7dd; color: #0f5132; border-radius: 8px; border: 1px solid #badbcc;">
            <i class="fas fa-check-circle"></i> {{ session('msg') }}
        </div>
    @endif

    <div class="custom-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        
        {{-- Header --}}
        <div class="card-header-custom" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <a href="{{ route('instructor.courses.index') }}" class="btn-back" style="color: #666; text-decoration: none; border: 1px solid #ddd; padding: 6px 12px; border-radius: 5px; background: #f9f9f9; font-size: 14px;">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <div>
                    <h1 class="page-title" style="margin: 0; font-size: 20px; color: #333;">Quản lý bài học</h1>
                    <small style="color: #0d6efd; font-weight: 600;">{{ $course->title }}</small>
                </div>
            </div>

            <a href="{{ route('instructor.lessons.create', $course->id) }}" class="btn btn-primary" style="padding: 8px 20px; background: #0d6efd; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
                <i class="fas fa-plus"></i> Thêm bài học
            </a>
        </div>

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">TT</th>
                        <th width="15%">Video Preview</th>
                        <th width="25%">Thông tin bài học</th>
                        <th width="25%">Học liệu đính kèm</th>
                        <th width="15%">Ngày tạo</th>
                        <th width="15%" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @if($lessons->count() > 0)
                        @foreach ($lessons as $lesson)
                        <tr>
                            {{-- 1. Thứ tự --}}
                            <td class="text-center">
                                <span class="badge-order">{{ $lesson->order ?? $loop->iteration }}</span>
                            </td>

                            {{-- 2. Preview Video --}}
                            <td>
                                @php
                                    $video_id = '';
                                    $is_youtube = false;
                                    if($lesson->video_url) {
                                        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $lesson->video_url, $match)) {
                                            $video_id = $match[1];
                                            $is_youtube = true;
                                        }
                                    }
                                @endphp

                                @if($is_youtube)
                                    <a href="{{ $lesson->video_url }}" target="_blank" class="video-preview-box" title="Xem trên Youtube">
                                        <img src="https://img.youtube.com/vi/{{ $video_id }}/mqdefault.jpg" alt="Thumb">
                                        <i class="fas fa-play-circle play-icon"></i>
                                    </a>
                                @elseif(!empty($lesson->video_url))
                                    <a href="{{ asset($lesson->video_url) }}" target="_blank" class="local-video-placeholder" title="Xem video">
                                        <i class="fas fa-file-video" style="font-size: 24px; margin-bottom: 5px; color: #6f42c1;"></i>
                                        <span>Video Upload</span>
                                    </a>
                                @else
                                    <div class="local-video-placeholder" style="background: #f8f9fa;">
                                        <i class="fas fa-video-slash" style="color: #ccc;"></i>
                                        <span style="color: #ccc;">Trống</span>
                                    </div>
                                @endif
                            </td>

                            {{-- 3. Thông tin bài học --}}
                            <td>
                                <div style="font-weight: 700; color: #333; font-size: 15px; margin-bottom: 5px;">
                                    {{ $lesson->title }}
                                </div>
                                <div style="font-size: 12px; color: #888;">
                                    <i class="far fa-clock"></i> {{ $lesson->duration ? $lesson->duration . ' phút' : '---' }}
                                </div>
                                @if($lesson->content)
                                    <div style="font-size: 12px; color: #666; margin-top: 5px; font-style: italic;">
                                        {{ Str::limit($lesson->content, 50) }}
                                    </div>
                                @endif
                            </td>

                            {{-- 4. Danh sách tài liệu --}}
                            <td>
                                @if($lesson->materials && $lesson->materials->count() > 0)
                                    <ul class="material-list">
                                        @foreach($lesson->materials as $material)
                                            <li class="material-item">
                                                {{-- Icon --}}
                                                @if($material->file_type == 'pdf') <i class="fas fa-file-pdf" style="color: #dc3545;"></i>
                                                @elseif(in_array($material->file_type, ['doc', 'docx'])) <i class="fas fa-file-word" style="color: #0d6efd;"></i>
                                                @elseif(in_array($material->file_type, ['zip', 'rar'])) <i class="fas fa-file-archive" style="color: #ffc107;"></i>
                                                @else <i class="fas fa-file-alt" style="color: #6c757d;"></i>
                                                @endif

                                                <a href="{{ asset($material->file_path) }}" target="_blank" title="{{ $material->filename }}">
                                                    {{ $material->filename }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span style="color: #ccc; font-size: 12px;">(Không có tài liệu)</span>
                                @endif
                            </td>

                            {{-- 5. Ngày tạo --}}
                            <td>
                                {{ $lesson->created_at ? $lesson->created_at->format('d/m/Y') : 'N/A' }}
                            </td>

                            {{-- 6. Hành động --}}
                            <td class="text-center">
                                <div style="display: flex; justify-content: center; gap: 8px;">
                                    {{-- Nút Sửa (Dùng thẻ A) --}}
                                    <a href="{{ route('instructor.lessons.edit', ['course' => $course->id, 'lesson' => $lesson->id]) }}" class="action-btn btn-edit" title="Sửa bài học">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    
                                    {{-- Nút Xóa (Dùng Form) --}}
                                    <form action="{{ route('instructor.lessons.destroy', ['course' => $course->id, 'lesson' => $lesson->id]) }}" method="POST" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa bài học này không?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" style="text-align:center; padding: 50px; color: #999;">
                                <i class="fas fa-folder-open" style="font-size: 40px; margin-bottom: 15px; display:block; color: #e9ecef;"></i>
                                <p style="margin: 0;">Khóa học này chưa có bài học nào.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection