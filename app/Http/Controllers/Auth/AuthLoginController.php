<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterCustomerRequest;
use App\Models\Customer;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthLoginController extends Controller
{
    // Phương thức để chuyển hướng người dùng đến trang xác thực của Google
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    // Phương thức xử lý thông tin người dùng trả về từ Google
    public function handleProviderCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $customer = Customer::where('email', $googleUser->email)->first();

            if (empty($customer)) {
                $customer = Customer::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Hash::make(Str::random(16))),
                ]);
            }
            Auth::guard('web')->login($customer, true);

            return redirect('/');
        } catch (Exception $e) {
            return redirect()->route('pages.login')->withErrors('Failed to login with Google.');
        }
    }

    // Logout web
    public function destroy(): RedirectResponse
    {
        Auth::guard('web')->logout();

        return redirect('/');
    }

    // trang login
    public function login()
    {
        return view('pages.login');
    }

    // xử lý logic khi người dùng gửi yêu cầu đăng nhập
    public function store(Request $request): RedirectResponse
    {
        //kiểm tra xem liệu người dùng có thể đăng nhập thành công hay không
        //Nếu ko thì mã lỗi sẽ được đặt trong session và user sẽ được chuyển hướng trở lại trang trước đó với thông báo thất bại
        if (!Auth::guard('web')->attempt($this->credentials($request))) {
            return redirect()->back()->with('error', 'Đăng nhập thất bại!');
        }

        //Thành công
        return redirect()->intended('/');
    }

    // Login username hoặc email
    protected function credentials(Request $request)
    {
        //Lấy gtri của trường login
        $login = $request->input('login');

        //xem có phải là email hoặc username hợp lệ k
        //nếu đúng, biến $field sẽ được thiết lập là 'email', ngược lại sẽ là 'username'.
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $field => $login,
            'password' => $request->input('password'),
        ];
    }

    // trang register
    public function register()
    {
        return view('pages.register');
    }

    // post register
    public function postRegister(RegisterCustomerRequest $request)
    {
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);
        $customer->wallet()->create([
            'balance' => 0,
        ]);
        Auth::guard('web')->login($customer, true);

        return redirect('/');
    }
}