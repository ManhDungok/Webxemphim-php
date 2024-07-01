<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Customer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //hiển thị danh sách khách hàng trong giao diện quản trị
    public function index(Request $request)
    {
        $users = Customer::paginate(config('view.default_pagination'));

        if ($request->search) { //Nếu có yêu cầu tìm kiếm thì tìm kiếm tên khách hàng
            // '%''%' đảm bảo rằng chuỗi tìm kiếm có thể xuất hiện ở bất kỳ vị trí nào trong tên khách hàng.
            $users = Customer::where('name', 'like', '%' . $request->search . '%')->paginate(config('view.default_pagination'));
            //Khi người dùng chuyển sang các trang phân trang khác, các tham số này được giữ nguyên trong URL để giữ lại KQ tìm kiếm và phân trang
            $users->appends(['search' => $request->search]);
        }

        $data = [
            'users' => $users,
        ];

        return view('admin.user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $data = [
            'user' => $user,
        ];

        return view('admin.user.profile', $data);
    }
}