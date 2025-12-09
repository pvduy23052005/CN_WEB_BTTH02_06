@extends('layout.layoutAdmin')

@section('main-content')
  <div class="main-content">
    <style>
      .categories-card {
        margin: 24px;
        padding: 20px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.07);
      }
      .categories-card h2 {
        margin: 0 0 16px;
        font-size: 20px;
        font-weight: 700;
      }
      .categories-table {
        width: 100%;
        border-collapse: collapse;
      }
      .categories-table th,
      .categories-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
      }
      .categories-table th {
        background: #f3f4f6;
        font-weight: 700;
        color: #111827;
      }
      .categories-table .actions {
        display: flex;
        gap: 8px;
      }
      .btn-ghost {
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #111827;
        text-decoration: none;
        font-size: 13px;
        transition: all 0.15s ease;
      }
      .btn-ghost:hover {
        border-color: #6366f1;
        color: #4338ca;
        box-shadow: 0 4px 10px rgba(99,102,241,0.18);
      }
      .actions-inline {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
      }
      .btn-primary {
        padding: 8px 14px;
        border-radius: 8px;
        border: none;
        background: linear-gradient(135deg,#6366f1,#4338ca);
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        box-shadow: 0 6px 18px rgba(67,56,202,0.28);
        transition: transform 0.12s ease, box-shadow 0.12s ease;
      }
      .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(67,56,202,0.32);
      }
      .categories-table tbody tr:nth-child(odd) {
        background: #fafafa;
      }
      .categories-table tbody tr:hover {
        background: #f0f4ff;
      }
      .categories-empty {
        text-align: center;
        color: #6b7280;
        padding: 16px 0;
      }
    </style>

    <div class="categories-card">
      <div class="actions-inline">
        <h2 style="flex:1;">Categories</h2>
        <a class="btn-primary" href="/admin/category/create">+ Create Category</a>
      </div>
      
      <table class="categories-table">
        <thead>
          <tr>
            <th>STT</th>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @if($categories->isEmpty())
            <tr>
              <td colspan="5" class="categories-empty">No categories found.</td>
            </tr>
          @else
            @foreach($categories as $index => $category)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description }}</td>
                <td>
                  <div class="actions">
                    <a class="btn-ghost" href= "/admin/category/edit/{{$category->id}}">Edit</a>
                  </div>
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>
@endsection