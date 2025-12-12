<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/base.css')}}">
  <link rel="stylesheet" href= "{{ asset('css/style.css')}}">
  <link rel="stylesheet" href= "{{ asset('css/courses.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <title>{{$title}}</title>
</head>
<body>
  @include('layout.sidebar')
  @include('layout.headerAdmin')
  @yield('main-content')
  <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>