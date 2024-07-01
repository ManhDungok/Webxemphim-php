<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Đánh dấu địa chỉ email của người dùng được xác thực là đã xác minh.
     */
    //erifyEmailController dùng để xác nhận địa chỉ email của người dùng sau khi họ nhận được email xác nhận từ hệ thống
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        //kiểm tra xem email của người dùng đã được xác nhận chưa. Nếu đã được xác nhận, người dùng sẽ được chuyển 
        //hướng đến trang chủ với tham số verified=1 (để thông báo rằng email đã được xác nhận).
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        }

        //đánh dấu email của người dùng là đã được xác nhận
        // Nếu thành công, sự kiện Verified sẽ được kích hoạt với đối tượng người dùng
        if ($request->user()->markEmailAsVerified()) {
            //Sự kiện này có thể được xử lý để thực hiện các tác vụ sau khi email của người dùng đã được xác nhận.
            event(new Verified($request->user()));
        }

        //verified=1 để có thể hiển thị thông báo xác nhận email thành công.
        return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
    }
}