<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Hiển thị lời nhắc xác minh email.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        //hasVerifiedEmail() ktra xem email của người dùng đã được xác thực hay chưa. 
        //Nếu đã xác thực, phương thức này trả về true hoặc false.
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(RouteServiceProvider::HOME)
            : view('auth.verify-email');
        //Nếu xác thực rồi (true) thì chuyển đến Home, nếu chưa thì trả về view auth.verify-email, 
        //(giao diện để yêu cầu người dùng xác thực email).

    }
}