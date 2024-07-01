<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Customer;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Movie;
use App\Models\WalletTopUp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $total_income = 0;

        //Chỉ lấy những giao dịch thành công
        $all_top_ups = WalletTopUp::all()->where('status', config('constants.top_up_status_success'));
        //Duyệt qua từng giao dịch nạp tiền thành công và cộng số tiền của mỗi giao dịch vào biến $total_income.
        foreach ($all_top_ups as $top_up) {
            $total_income += $top_up->amount;
        }
        //Lấy danh sách các giao dịch nạp tiền và phân trang
        $top_ups = WalletTopUp::paginate(config('view.default_pagination'));

        $data = [
            'total_income' => $total_income,
            'top_ups' => $top_ups,
        ];

        return view('admin.transaction.index', $data);
    }
    //tính toán tổng doanh thu từ các đơn đặt hàng phim và hiển thị danh sách phim cùng tổng doanh thu trên giao diện quản trị.
    public function showMovies()
    {
        //Dòng này lấy tất cả các phim từ bảng Movie và đếm số lượng đơn đặt hàng (orders_count) của mỗi phim.
        $movies = Movie::withCount('orders')->get();
        $totalRevenue = 0;

        foreach ($movies as $movie) { //lặp qua từng phần tử trong mảng
            //nhân số lượng đơn đặt hàng (orders_count) với giá của phim (price), sau đó cộng vào biến $totalRevenue.
            $totalRevenue += $movie->orders_count * $movie->price;
        }

        //chia sẻ biến $totalRevenue với tất cả các view, giúp chúng có thể truy cập được biến này.
        View::share('totalRevenue', $totalRevenue);
        return view('admin.transaction.statics', compact('movies'));
    }
}