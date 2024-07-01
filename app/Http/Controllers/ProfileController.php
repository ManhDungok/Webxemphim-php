<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Nation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class ProfileController extends Controller
{
    public function __construct()
    {
        $nations = Nation::all();
        View::share('nations', $nations);

        $categories = Category::all();
        View::share('categories', $categories);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Cập nhật thông tin hồ sơ của người dùng.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * xử lý việc xóa tài khoản người dùng sau khi họ đã xác nhận bằng mật khẩu hiện tại
     */
    public function destroy(Request $request): RedirectResponse
    {
        //yêu cầu trường password phải được cung cấp và phải trùng khớp với mkhẩu hiện tại của user (current_password).
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        //thông tin người dùng từ request và tiến hành đăng xuất người dùng khỏi hệ thống.
        $user = $request->user();
        Auth::logout();

        //Thực hiện xóa tài khoản người dùng từ cơ sở dữ liệu.
        $user->delete();

        //Huỷ phiên đăng nhập hiện tại và tái tạo mã thông báo phiên (session token)
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function profile_settings()
    {
        return view('pages.setting', [
            'user' => Auth::guard('web')->user(),
        ]);
    }

    public function update_profile()
    {
        $current_user = Auth::guard('web')->user();

        $current_user->name = request()->name;
        $current_user->email = request()->email;
        $current_user->username = request()->username;
        $current_user->password = request()->password ? Hash::make(request()->password) : $current_user->password;

        $current_user->save();

        return redirect()->route('web.profile-settings')->with('success', 'Cập nhật thông tin thành công.');
    }
}