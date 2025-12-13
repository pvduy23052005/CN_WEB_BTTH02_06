@extends('layout.layoutAdmin')



@section('main-content')



<div class="admin-content-wrapper">



    {{-- Hiển thị thông báo thành công --}}

    @if(session('msg'))

        <div class="alert alert-success" style="padding: 15px; margin-bottom: 20px; background: #d1e7dd; color: #0f5132; border-radius: 8px; border: 1px solid #badbcc;">

            <i class="fas fa-check-circle"></i> {{ session('msg') }}

        </div>

    @endif



    <div class="custom-card">

        <div class="card-header-custom" style="display: flex; justify-content: space-between; align-items: center;">

            <div style="display: flex; align-items: center; gap: 15px;">

                {{-- Nút quay lại danh sách khóa học --}}

                <a href="{{ route('instructor.courses.index') }}" class="btn-back" style="color: #666; font-size: 14px; text-decoration: none; border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px; background: #f9f9f9;">

                    <i class="fas fa-arrow-left"></i> Quay lại

                </a>

               

                <div>

                    <h1 class="page-title" style="margin: 0; font-size: 20px;">

                        Quản lý bài học

                    </h1>

                    <small style="color: #17a2b8; font-weight: 600;">

                        <i class="fas fa-book"></i> Khóa học: {{ $course->title }}

                    </small>

                </div>

            </div>



            {{-- Nút thêm bài học mới --}}

            <a href="{{ route('instructor.lessons.create', $course->id) }}" class="btn-add-new">

                <i class="fas fa-plus"></i> Thêm bài học

            </a>

        </div>



        <div class="table-responsive">

            <table class="table-custom">

                <thead>

                    <tr>

                        <th width="5%">TT</th>

                        <th width="25%">Tên bài học</th>

                        {{-- Đổi tên cột Video thành Học liệu để chứa cả Video và Tài liệu --}}

                        <th width="20%">Học liệu (Video/File)</th>

                        <th width="25%">Nội dung mô tả</th>

                        <th width="10%">Ngày tạo</th>

                        <th width="15%" class="text-center">Hành động</th>

                    </tr>

                </thead>

                <tbody>

                    @if(count($lessons) > 0)

                        @foreach ($lessons as $lesson)

                        <tr>

                            {{-- Hiển thị thứ tự sắp xếp (order) --}}

                            <td style="text-align: center;">

                                <span style="background: #eee; padding: 2px 8px; border-radius: 4px; font-weight: bold;">

                                    {{ $lesson->order ?? $loop->iteration }}

                                </span>

                            </td>



                            <td>

                                <b>{{ $lesson->title }}</b>

                                <div style="font-size: 12px; color: #888; margin-top: 4px;">

                                    <i class="far fa-clock"></i> {{ $lesson->duration ? $lesson->duration . ' phút' : '---' }}

                                </div>

                            </td>



                            <td>

                                <div style="display: flex; flex-direction: column; gap: 5px;">

                                    {{-- 1. Hiển thị Video --}}

                                    @if(!empty($lesson->video_url))

                                        <a href="{{ asset($lesson->video_url) }}" target="_blank" style="color: #d63384; font-weight: 500; text-decoration: none; font-size: 13px;">

                                            <i class="fas fa-play-circle"></i> Xem Video

                                        </a>

                                    @endif



                                    {{-- 2. Hiển thị Tài liệu (Mới thêm) --}}

                                    @if(!empty($lesson->document_url))

                                        <a href="{{ asset($lesson->document_url) }}" target="_blank" style="color: #198754; font-weight: 500; text-decoration: none; font-size: 13px;">

                                            <i class="fas fa-file-download"></i> Tải tài liệu

                                        </a>

                                    @endif



                                    {{-- Nếu không có cả 2 --}}

                                    @if(empty($lesson->video_url) && empty($lesson->document_url))

                                        <span style="color: #ccc; font-style: italic; font-size: 12px;">(Trống)</span>

                                    @endif

                                </div>

                            </td>



                            <td>

                                {{-- Cắt ngắn nội dung --}}

                                <span style="color: #555; font-size: 13px;">

                                    {{ Str::limit($lesson->content, 80) }}

                                </span>

                            </td>



                            <td>

                                {{ $lesson->created_at ? $lesson->created_at->format('d/m/Y') : 'N/A' }}

                            </td>



                            <td class="text-center">

                                <div class="action-group" style="display: flex; justify-content: center; gap: 5px;">

                                   

                                    {{-- Nút Sửa --}}

                                    <a href="{{ route('instructor.lessons.edit', ['course' => $course->id, 'lesson' => $lesson->id]) }}" class="action-btn btn-edit" title="Sửa bài học">

                                        <i class="fas fa-pen"></i>

                                    </a>

                                   

                                    {{-- Nút Xóa --}}

                                    <form action="{{ route('instructor.lessons.destroy', ['course' => $course->id, 'lesson' => $lesson->id]) }}" method="POST" style="margin:0;">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit" class="action-btn btn-delete" title="Xóa bài học" onclick="return confirm('Bạn có chắc chắn muốn xóa bài học này không?')">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @endforeach

                    @else

                        <tr>

                            <td colspan="6" style="text-align:center; padding: 40px; color: #999;">

                                <i class="fas fa-folder-open" style="font-size: 40px; margin-bottom: 10px; display:block; color: #ddd;"></i>

                                Khóa học này chưa có bài học nào.

                            </td>

                        </tr>

                    @endif

                </tbody>

            </table>

        </div>

    </div>

</div>



@endsection