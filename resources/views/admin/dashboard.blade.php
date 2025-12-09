@extends('layout.layoutAdmin')

@section('main-content')
  <div class="main-content">
    @foreach ($courses as $course)
      <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
          <h3>{{ $course->title }}</h3>
          <p>{{ $course->description }}</p>
          <p>Giá: {{ $course->price }}</p>
          <p>Thời lượng: {{ $course->duration_weeks }} tuần</p>
          <p>Cấp độ: {{ $course->level }}</p>
      </div>
    @endforeach
  </div>
@endsection