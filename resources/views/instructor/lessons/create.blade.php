@extends('layout.layoutAdmin')



@section('main-content')



<div class="admin-content-wrapper">

   

    <div class="course-form-card">

        <h1>Thêm Bài Học Mới</h1>

        <div style="text-align: center; color: #666; margin-bottom: 20px;">

            <span>Đang thêm vào khóa học: <b style="color: #17a2b8;">{{ $course->title }}</b></span>

        </div>



        {{-- Form gửi đến route lessons.store với tham số course id --}}

        {{-- QUAN TRỌNG: enctype="multipart/form-data" để upload file --}}

        <form action="{{ route('instructor.lessons.store', $course->id) }}" method="POST" enctype="multipart/form-data">

            @csrf



            {{-- 1. Tiêu đề bài học --}}

            <div class="form-group">

                <label for="title">Tiêu đề bài học <span class="text-danger">*</span></label>

                <input type="text" name="title" id="title" class="form-control"

                       placeholder="Nhập tiêu đề bài học..."

                       value="{{ old('title') }}" required>

                @error('title') <span class="text-danger">{{ $message }}</span> @enderror

            </div>



            {{-- 2. Thứ tự và Thời lượng --}}

            <div class="form-row">

                {{-- Cột Thứ tự --}}

                <div class="form-col">

                    <div class="form-group">

                        <label for="order">Thứ tự hiển thị (Số nguyên)</label>

                        <input type="number" name="order" id="order" class="form-control"

                               placeholder="VD: 1, 2, 3..."

                               value="{{ old('order') }}">

                        <small class="text-muted">Để trống sẽ tự động xếp cuối.</small>

                        @error('order') <span class="text-danger">{{ $message }}</span> @enderror

                    </div>

                </div>



                {{-- Cột Thời lượng (Tùy chọn) --}}

                <div class="form-col">

                    <div class="form-group">

                        <label for="duration">Thời lượng (Phút)</label>

                        <input type="number" name="duration" id="duration" class="form-control"

                               placeholder="VD: 45"

                               value="{{ old('duration') }}">

                    </div>

                </div>

            </div>



            {{-- 3. Xử lý Video (File hoặc Link) --}}

            <div class="form-group" style="background: #f9f9f9; padding: 15px; border-radius: 5px; border: 1px dashed #ccc;">

                <label style="font-weight: bold; color: #17a2b8;">

                    <i class="fas fa-video"></i> Nội dung Video

                </label>

               

                <div class="form-row">

                    {{-- Upload file --}}

                    <div class="form-col">

                        <label for="video_file">Upload Video (Ưu tiên)</label>

                        <input type="file" name="video_file" id="video_file" class="form-control"

                               style="padding: 9px;" accept="video/*">

                    </div>

                   

                    {{-- Hoặc nhập Link --}}

                    <div class="form-col">

                        <label for="video_url">Hoặc dán Link (Youtube/Vimeo)</label>

                        <input type="text" name="video_url" id="video_url" class="form-control"

                               placeholder="https://..." value="{{ old('video_url') }}">

                    </div>

                </div>

                @error('video_url') <span class="text-danger">{{ $message }}</span> @enderror

            </div>



            {{-- 4. [MỚI] Upload Tài liệu đính kèm --}}

            <div class="form-group" style="background: #eef2f5; padding: 15px; border-radius: 5px; margin-top: 15px; border: 1px dashed #ccc;">

                <label style="font-weight: bold; color: #2c3e50;">

                    <i class="fas fa-file-alt"></i> Tài liệu đính kèm (PDF, Word, Zip...)

                </label>

                <div class="form-row">

                    <div class="form-col" style="width: 100%;">

                        <input type="file" name="document_file" id="document_file" class="form-control"

                               style="padding: 9px;" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">

                        <small class="text-muted">Tài liệu bổ trợ cho bài học (Không bắt buộc).</small>

                    </div>

                </div>

                @error('document_file') <span class="text-danger">{{ $message }}</span> @enderror

            </div>



            {{-- 5. Nội dung bài học --}}

            <div class="form-group">

                <label for="content">Nội dung chi tiết (Văn bản)</label>

                <textarea name="content" id="content" class="form-control" rows="8"

                          placeholder="Nhập nội dung bài giảng...">{{ old('content') }}</textarea>

                @error('content') <span class="text-danger">{{ $message }}</span> @enderror

            </div>



            {{-- 6. Nút bấm --}}

            <div class="form-actions" style="display: flex; justify-content: space-between; align-items: center;">

                <div>

                    <a href="{{ route('instructor.lessons.index', $course->id) }}" class="btn btn-secondary">

                        <i class="fas fa-arrow-left"></i> Quay lại

                    </a>

                </div>

               

                <div style="display: flex; gap: 10px;">

                    {{-- Nút Reset form --}}

                    <button type="reset" class="btn btn-warning" style="color: white;">

                        <i class="fas fa-sync"></i> Nhập lại

                    </button>

                   

                    {{-- Nút Lưu --}}

                    <button type="submit" class="btn btn-success">

                        <i class="fas fa-plus-circle"></i> Thêm bài học

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>



@endsection