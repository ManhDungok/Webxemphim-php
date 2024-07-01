<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Hiển thị chế độ view xác nhận mật khẩu.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Xác nhận mật khẩu của người dùng,sau đó lưu thông tin xác thực vào session trước 
     * khi chuyển hướng người dùng đến một địa chỉ mong muốn
     */
    public function store(Request $request): RedirectResponse
    {
        //validate() sử dụng email và password từ request để kiểm tra tính hợp lệ của người dùng.
        if (!Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            //Nếu xác thực không thành công, một ValidationException sẽ được ném ra với thông báo lỗi auth.password.
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        //Sau khi xác thực thành công, mã người dùng sẽ lưu thời điểm xác thực (time()) vào session với 
        //key là 'auth.password_confirmed_at'
        //sử dụng để kiểm tra xem người dùng đã xác thực mật khẩu trong một khoảng thời gian nhất định hay chưa.
        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}