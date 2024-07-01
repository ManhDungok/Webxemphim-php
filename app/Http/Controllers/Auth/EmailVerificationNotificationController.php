<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * xác minh xem người dùng đã xác thực email chưa
     */
    public function store(Request $request): RedirectResponse
    {
        //Nếu xác thực rồi (true) thì chuyển đến Home, nếu chưa thì trở lại trang trc đó
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        //sendEmailVerificationNotification() là một phương thức trong Laravel để gửi email xác thực đến người dùng
        $request->user()->sendEmailVerificationNotification();

        //Quay trở lại và cung cấp thông báo:
        //thông báo cho người dùng rằng liên kết xác thực đã được gửi thành công.
        return back()->with('status', 'verification-link-sent');
    }
}