<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Hiển thị chế độ view đăng nhập.
     */

    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * xử lý lưu trữ thông tin từ form vào cơ sở dữ liệu  
     */
    //xác thực dữ liệu từ form đăng nhập theo các quy tắc xác thực được định nghĩa trước (VD như ycầu các trường email và password).
    public function store(LoginRequest $request): RedirectResponse
    {
        //Nếu xác thực thành công, người dùng sẽ được đăng nhập vào hệ thống
        $request->authenticate();

        // Phương thức này được sử dụng để tạo lại session
        $request->session()->regenerate();

        //Đăng nhập thành ông thì chuyển đến HOME
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    // đăng xuất = cách xóa session
    public function destroy(Request $request): RedirectResponse
    {
        //Dòng này đăng xuất người dùng khỏi guard có tên là 'admin'
        Auth::guard('admin')->logout();

        //hủy bỏ tất cả các session dữ liệu hiện tại của người dùng
        $request->session()->invalidate();

        // Dòng này tạo lại token bảo mật cho session mới.
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}