<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Danh sách các đầu vào không bao giờ được hiển thị trong phiên có ngoại lệ xác thực.     *
     * @var array<int, string>
     */
    //là những trường thường chứa thông tin nhạy cảm, không nên bị lưu trữ lại trong 
    //session để tránh rủi ro bảo mật.
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Đăng ký các cuộc gọi lại xử lý ngoại lệ cho ứng dụng.     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}