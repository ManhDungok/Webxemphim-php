<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Hiển thị chế độ view đặt lại mật khẩu.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Xử lý yêu cầu mật khẩu mới đến.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'], //Yêu cầu trường 'token' phải tồn tại và không được để trống.
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        //Thiết lập lại mk ng dùng, nếu k trả về lỗi
        $status = Password::reset(
            // $request chỉ lấy các trường 'email', 'password', 'password_confirmation', 'token' từ request
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                //thay đổi và lưu lại mật khẩu mới của người dùng.
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    //Tạo một token ngẫu nhiên cho remember_token để duy trì phiên đăng nhập.
                    'remember_token' => Str::random(60),
                ])->save();

                //Phát ra thông báo mkhau đã dc reset thành công
                event(new PasswordReset($user));
            }
        );


        return $status == Password::PASSWORD_RESET //quá trình reset mật khẩu đã thành công.
            //người dùng sẽ được chuyển hướng đến trang login 
            ? redirect()->route('login')->with('status', __($status))
            //nếu quá trình reset mật khẩu không thành công),user sẽ được chuyển hướng trở lại trang trước đó 
            : back()->withInput($request->only('email'))
            //Hiển thị thông báo lỗi
            ->withErrors(['email' => __($status)]);
    }
}