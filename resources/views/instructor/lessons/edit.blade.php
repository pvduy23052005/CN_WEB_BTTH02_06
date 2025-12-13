@extends('layout.layoutAdmin')

@section('main-content')


<div class="admin-content-wrapper">

    <div class="course-form-card" style="background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <div class="card-header" style="border-bottom: 2px solid #f0f2f5; padding-bottom: 15px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="margin: 0; font-size: 22px; color: #2c3e50;"><i class="fas fa-edit"></i> Cập Nhật Bài Học</h1>
                <div style="color: #666; margin-top: 5px; font-size: 14px;">
                    Khóa học: <b style="color: #17a2b8;">{{ $course->title }}</b>
                </div>
            </div>
            <span style="background: #eee; padding: 5px 10px; border-radius: 4px; font-weight: bold; color: #555;">ID: #{{ $lesson->id }}</span>
        </div>

        <form action="{{ route('instructor.lessons.update', ['course' => $course->id, 'lesson' => $lesson->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- 1. Tiêu đề --}}
            <div class="form-group">
                <label for="title" style="font-weight: 600;">Tiêu đề bài học <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control"
                       placeholder="Nhập tên bài học..."
                       value="{{ old('title', $lesson->title) }}" required>
                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- 2. Thứ tự & Thời lượng --}}
            <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 15px;">
                <div class="form-col" style="flex: 1;">
                    <div class="form-group">
                        <label for="order" style="font-weight: 600;">Thứ tự hiển thị</label>
                        <input type="number" name="order" id="order" class="form-control"
                               placeholder="VD: 1" value="{{ old('order', $lesson->order) }}">
                    </div>
                </div>
                <div class="form-col" style="flex: 1;">
                    <div class="form-group">
                        <label for="duration" style="font-weight: 600;">Thời lượng (Phút)</label>
                        <input type="number" name="duration" id="duration" class="form-control"
                               placeholder="VD: 45" value="{{ old('duration', $lesson->duration) }}">
                    </div>
                </div>
            </div>

            {{-- 3. VIDEO BÀI GIẢNG --}}
            <div class="form-group" style="background: #e7f1ff; padding: 20px; border-radius: 8px; border: 1px dashed #0d6efd; margin-bottom: 20px;">
                <label style="font-weight: bold; color: #0d6efd; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-video"></i> VIDEO BÀI GIẢNG
                </label>

                {{-- HIỂN THỊ VIDEO HIỆN TẠI (Nếu có) --}}
                @if($lesson->video_url)
                    <div id="current-video-display" class="current-media-box">
                        <p style="margin: 0 0 10px 0; font-weight: 600; color: #555; font-size: 13px;">Video hiện tại:</p>
                        
                        @php
                            // Logic check Youtube
                            $video_id = '';
                            $is_youtube = false;
                            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $lesson->video_url, $match)) {
                                $video_id = $match[1];
                                $is_youtube = true;
                            }
                        @endphp

                        @if($is_youtube)
                            <div style="position: relative; display: inline-block; max-width: 200px;">
                                <img src="https://img.youtube.com/vi/{{ $video_id }}/mqdefault.jpg" style="width: 100%; border-radius: 4px;">
                                <i class="fas fa-play-circle" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #fff; font-size: 30px;"></i>
                            </div>
                            <div style="margin-top: 5px; font-size: 12px;"><a href="{{ $lesson->video_url }}" target="_blank">Xem trên Youtube</a></div>
                        @else
                             {{-- Local Video --}}
                             <video width="320" height="180" controls style="border-radius: 4px; background: #000;">
                                <source src="{{ asset($lesson->video_url) }}" type="video/mp4">
                                Trình duyệt không hỗ trợ.
                             </video>
                        @endif
                    </div>
                @endif
                
                <div class="form-row" style="display: flex; gap: 20px; margin-top: 15px; align-items: flex-start;">
                    {{-- Upload file --}}
                    <div class="form-col" style="flex: 1;">
                        <label for="video_file" style="font-weight: 600;">Tải video mới (Ghi đè)</label>
                        <input type="file" name="video_file" id="video_file" class="form-control"
                               style="padding: 8px;" accept="video/*">
                    </div>
                    
                    <div style="padding-top: 30px; font-weight: bold; color: #999;">HOẶC</div>

                    {{-- Nhập Link --}}
                    <div class="form-col" style="flex: 1;">
                        <label for="video_url" style="font-weight: 600;">Link Youtube mới</label>
                        <input type="text" name="video_url" id="video_url" class="form-control"
                               placeholder="https://youtube.com/..." value="{{ old('video_url', $lesson->video_url) }}">
                    </div>
                </div>

                {{-- KHU VỰC PREVIEW VIDEO MỚI (Khi chọn file/link mới) --}}
                <div id="video-preview-area">
                    <p style="color: #fff; font-size: 12px; margin-bottom: 5px; text-align: left;">Preview Video Mới:</p>
                    <video id="local-video-player" controls>Your browser does not support the video tag.</video>
                    <div style="position: relative; display: inline-block; width: 100%;">
                        <img id="youtube-preview-img" src="" alt="Youtube Thumb">
                        <i class="fas fa-play-circle yt-play-btn" id="yt-icon"></i>
                    </div>
                </div>

                @error('video_file') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- 4. TÀI LIỆU (CÓ LIST VÀ PREVIEW) --}}
            <div class="form-group" style="background: #f0fdf4; padding: 20px; border-radius: 8px; border: 1px dashed #198754; margin-bottom: 20px;">
                <label style="font-weight: bold; color: #198754; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-file-alt"></i> TÀI LIỆU ĐÍNH KÈM
                </label>

                {{-- DANH SÁCH TÀI LIỆU HIỆN CÓ --}}
                @if($lesson->materials && $lesson->materials->count() > 0)
                    <div style="margin: 10px 0;">
                        <p style="font-weight: 600; font-size: 13px; color: #555; margin-bottom: 5px;">Danh sách file hiện có:</p>
                        <div style="border: 1px solid #ddd; border-radius: 4px; overflow: hidden;">
                            @foreach($lesson->materials as $mat)
                                <div class="material-list-item">
                                    @if($mat->file_type == 'pdf') <i class="fas fa-file-pdf" style="color: #dc3545;"></i>
                                    @elseif(in_array($mat->file_type, ['doc', 'docx'])) <i class="fas fa-file-word" style="color: #0d6efd;"></i>
                                    @else <i class="fas fa-file-alt" style="color: #666;"></i>
                                    @endif
                                    
                                    <div style="flex: 1;">
                                        <a href="{{ asset($mat->file_path) }}" target="_blank" style="text-decoration: none; color: #333; font-weight: 500;">
                                            {{ $mat->filename }}
                                        </a>
                                    </div>
                                    <span style="font-size: 11px; color: #999;">{{ $mat->uploaded_at }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div style="margin-top: 15px;">
                    <label for="document_file" style="font-weight: 600;">Thêm tài liệu mới</label>
                    <input type="file" name="document_file" id="document_file" class="form-control"
                           style="padding: 8px;" 
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
                           
                    <small class="text-muted" style="display: block; margin-top: 5px;">
                        <i class="fas fa-plus"></i> Chọn file để thêm vào danh sách tài liệu.
                    </small>
                </div>

                {{-- PREVIEW TÀI LIỆU MỚI --}}
                <div id="doc-preview-area">
                    <i id="doc-icon" class="fas fa-file preview-icon"></i>
                    <div style="display: flex; flex-direction: column;">
                        <span id="doc-name" style="font-weight: 600; color: #333;">filename.pdf</span>
                        <span id="doc-size" style="font-size: 11px; color: #777;">0 MB</span>
                        <span style="font-size: 10px; color: #198754; font-weight: bold;">(Sẽ được thêm mới)</span>
                    </div>
                </div>
            </div>

            {{-- 5. Nội dung --}}
            <div class="form-group">
                <label for="content" style="font-weight: 600;">Nội dung chi tiết</label>
                <textarea name="content" id="content" class="form-control" rows="5"
                          placeholder="Nhập nội dung...">{{ old('content', $lesson->content) }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="form-actions" style="display: flex; justify-content: space-between; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                <a href="{{ route('instructor.lessons.index', $course->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 10px 30px; font-weight: bold;">
                    <i class="fas fa-save"></i> Cập Nhật Bài Học
                </button>
            </div>
        </form>
    </div>
</div>


@endsection