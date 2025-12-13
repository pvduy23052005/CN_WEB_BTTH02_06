@extends('layout.layoutAdmin')

@section('main-content')

<div class="admin-content-wrapper">

    <div class="course-form-card" style="background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <div class="card-header" style="border-bottom: 2px solid #f0f2f5; padding-bottom: 15px; margin-bottom: 25px;">
            <h1 style="margin: 0; font-size: 22px; color: #2c3e50;"><i class="fas fa-plus-circle"></i> Thêm Bài Học Mới</h1>
            <div style="color: #666; margin-top: 5px; font-size: 14px;">
                Đang thêm vào khóa học: <b style="color: #17a2b8;">{{ $course->title }}</b>
            </div>
        </div>

        <form action="{{ route('instructor.lessons.store', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- 1. Tiêu đề --}}
            <div class="form-group">
                <label for="title" style="font-weight: 600;">Tiêu đề bài học <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control"
                       placeholder="Nhập tên bài học..."
                       value="{{ old('title') }}" required>
                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- 2. Thứ tự & Thời lượng --}}
            <div class="form-row" style="display: flex; gap: 20px; margin-bottom: 15px;">
                <div class="form-col" style="flex: 1;">
                    <div class="form-group">
                        <label for="order" style="font-weight: 600;">Thứ tự hiển thị</label>
                        <input type="number" name="order" id="order" class="form-control"
                               placeholder="VD: 1" value="{{ old('order') }}">
                    </div>
                </div>
                <div class="form-col" style="flex: 1;">
                    <div class="form-group">
                        <label for="duration" style="font-weight: 600;">Thời lượng (Phút)</label>
                        <input type="number" name="duration" id="duration" class="form-control"
                               placeholder="VD: 45" value="{{ old('duration') }}">
                    </div>
                </div>
            </div>

            {{-- 3. VIDEO BÀI GIẢNG (CÓ PREVIEW) --}}
            <div class="form-group" style="background: #e7f1ff; padding: 20px; border-radius: 8px; border: 1px dashed #0d6efd; margin-bottom: 20px;">
                <label style="font-weight: bold; color: #0d6efd; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-video"></i> VIDEO BÀI GIẢNG
                </label>
                
                <div class="form-row" style="display: flex; gap: 20px; margin-top: 15px; align-items: flex-start;">
                    {{-- Upload file --}}
                    <div class="form-col" style="flex: 1;">
                        <label for="video_file" style="font-weight: 600;">Tải video lên (mp4)</label>
                        <input type="file" name="video_file" id="video_file" class="form-control"
                               style="padding: 8px;" accept="video/*">
                    </div>
                    
                    <div style="padding-top: 30px; font-weight: bold; color: #999;">HOẶC</div>

                    {{-- Nhập Link --}}
                    <div class="form-col" style="flex: 1;">
                        <label for="video_url" style="font-weight: 600;">Dán Link Youtube</label>
                        <input type="text" name="video_url" id="video_url" class="form-control"
                               placeholder="https://youtube.com/..." value="{{ old('video_url') }}">
                    </div>
                </div>

                {{-- KHU VỰC PREVIEW VIDEO --}}
                <div id="video-preview-area">
                    <p style="color: #fff; font-size: 12px; margin-bottom: 5px; text-align: left;">Preview:</p>
                    
                    {{-- Player cho video upload --}}
                    <video id="local-video-player" controls>
                        Your browser does not support the video tag.
                    </video>

                    {{-- Ảnh thumb cho Youtube --}}
                    <div style="position: relative; display: inline-block; width: 100%;">
                        <img id="youtube-preview-img" src="" alt="Youtube Thumb">
                        <i class="fas fa-play-circle yt-play-btn" id="yt-icon"></i>
                    </div>
                </div>

                @error('video_file') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            {{-- 4. TÀI LIỆU (CÓ PREVIEW) --}}
            <div class="form-group" style="background: #f0fdf4; padding: 20px; border-radius: 8px; border: 1px dashed #198754; margin-bottom: 20px;">
                <label style="font-weight: bold; color: #198754; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-file-alt"></i> TÀI LIỆU ĐÍNH KÈM
                </label>
                <div style="margin-top: 10px;">
                    <input type="file" name="document_file" id="document_file" class="form-control"
                           style="padding: 8px;" 
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.jpg,.png"> {{-- Thêm jpg, png nếu muốn cho up ảnh làm tài liệu --}}
                </div>

                {{-- KHU VỰC PREVIEW TÀI LIỆU --}}
                <div id="doc-preview-area">
                    <i id="doc-icon" class="fas fa-file preview-icon"></i>
                    <div style="display: flex; flex-direction: column;">
                        <span id="doc-name" style="font-weight: 600; color: #333;">filename.pdf</span>
                        <span id="doc-size" style="font-size: 11px; color: #777;">0 MB</span>
                    </div>
                </div>
            </div>

            {{-- 5. Nội dung --}}
            <div class="form-group">
                <label for="content" style="font-weight: 600;">Nội dung chi tiết</label>
                <textarea name="content" id="content" class="form-control" rows="5"
                          placeholder="Nhập nội dung...">{{ old('content') }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="form-actions" style="display: flex; justify-content: space-between; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                <a href="{{ route('instructor.lessons.index', $course->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <button type="submit" class="btn btn-success" style="padding: 10px 30px; font-weight: bold;">
                    <i class="fas fa-save"></i> Lưu Bài Học
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT XỬ LÝ PREVIEW --}}
<script>
   
</script>

@endsection