@extends('layout.layoutAdmin')

@section('main-content')
  <div class="main-content">
    <style>
      .form-card {
        margin: 24px;
        padding: 20px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.07);
        max-width: 560px;
      }
      .form-card h2 {
        margin: 0 0 16px;
        font-size: 20px;
        font-weight: 700;
      }
      .form-group {
        margin-bottom: 14px;
      }
      .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 6px;
      }
      .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
      }
      .form-control:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
      }
      .btn-primary {
        padding: 10px 16px;
        border-radius: 8px;
        border: none;
        background: linear-gradient(135deg,#6366f1,#4338ca);
        color: #fff;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 8px 20px rgba(67,56,202,0.25);
        transition: transform 0.12s ease, box-shadow 0.12s ease;
      }
      .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 26px rgba(67,56,202,0.32);
      }
      .alert {
        margin-bottom: 12px;
        padding: 10px 12px;
        border-radius: 8px;
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecdd3;
      }
    </style>

    <div class="form-card">
      <h2>Edit Category</h2>

      <form method="POST" action="/admin/category/edit/{{ $category->id }}">
        @csrf
        <div class="form-group">
          <label class="form-label" for="name">Name <span style="color:#ef4444">*</span></label>
          <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="description">Description</label>
          <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
        </div>

        <button class="btn-primary" type="submit">Save </button>
      </form>
    </div>
  </div>
@endsection