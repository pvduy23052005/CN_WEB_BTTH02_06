<?php

namespace App\Models;

// Import các trait và class cần thiết
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Import SoftDeletes nếu bạn muốn dùng nó thay vì cột 'deleted' thủ công
// use Illuminate\Database\Eloquent\SoftDeletes; 

class User extends Authenticatable
{
    // Nếu bạn muốn dùng SoftDeletes, hãy bỏ comment dòng sau:
    // use SoftDeletes; 

    /**
     * Tên bảng liên kết với Model.
     * Mặc định Laravel sẽ tìm 'users', nhưng bạn đã tạo bảng tên là 'users' nên không cần thiết.
     * Tuy nhiên, nếu bạn dùng tên bảng 'user' (số ít) thì cần khai báo:
     * protected $table = 'user'; 
     */
    protected $table = 'users';

    /**
     * Các thuộc tính có thể được gán giá trị hàng loạt (Mass Assignable).
     * Bỏ qua id, created_at, updated_at, deleted.
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'fullname',
        'role',
        // 'created_at', // Mặc định timestamps() sẽ tự quản lý
        // 'deleted', // Quản lý bởi logic thủ công hoặc SoftDeletes
    ];

    /**
     * Các thuộc tính nên được ẩn khi chuyển đổi Model thành mảng hoặc JSON.
     * Thường dùng để ẩn password và các token nhạy cảm.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Các thuộc tính nên được ép kiểu (cast) sang kiểu dữ liệu cụ thể.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'deleted' => 'boolean', // Nếu muốn ép kiểu boolean
    ];
    
    // Nếu bạn dùng cột 'deleted' thủ công thay vì SoftDeletes:
    
    /**
     * Thiết lập Global Scope để chỉ lấy những user chưa bị xóa (deleted = false).
     * Bất cứ truy vấn nào tới User::query() sẽ tự động thêm điều kiện này.
     */
    protected static function booted()
    {
        static::addGlobalScope('active', function ($builder) {
            $builder->where('deleted', false);
        });
    }

    /**
     * Phương thức để lấy tất cả user, kể cả những user đã bị xóa (deleted = true).
     */
    public static function withDeleted()
    {
        return static::withoutGlobalScope('active');
    }
}