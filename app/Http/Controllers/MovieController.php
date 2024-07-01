<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Favorite;
use App\Models\Movie;
use App\Models\Nation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;

class MovieController extends Controller
{
    protected $nations;
    protected $categories;

    public function __construct()
    {
        $nations = Nation::all();
        View::share('nations', $nations);

        $categories = Category::all();
        View::share('categories', $categories);

        //gán dữ liệu vào các thuộc tính ($nations và $categories) 
        $this->nations = $nations;
        $this->categories = $categories;
    }

    /**
     * Display a listing of the resource.
     */
    //Hiển thị tất cả phim
    public function index()
    {
        $movies = Movie::all()->where('status', config('constants.status_active'));

        $trending_movies = [];

        //Lặp qua từng danh sách các đối tượng phim ($movies),
        // lọc ra những phim nổi bật (trending movies) từ danh sách phim ban đầu.
        foreach ($movies as $movie) {
            if ($movie->trending) {
                $trending_movies[] = $movie;
            }
        }

        $data = [
            'movies' => $movies,
            'trending_movies' => $trending_movies,
        ];

        // Truyền dữ liệu vào view
        return view('pages.main', $data);
    }

    /**
     * Trang chi tiết phim.
     */
    public function show(int $id)
    {
        $movie = Movie::find($id);

        $data = [
            'movie' => $movie,
        ];

        return view('pages.movie', $data);
    }

    /**
     * Trang danh mục phim.
     */
    public function category(int $id)
    {
        //Lọc các phim có category_id bằng $id được truyền vào.
        //Lọc các phim có trạng thái hoạt động
        //get(): Thực hiện truy vấn và lấy tất cả các kết quả phù hợp.
        $movies = Movie::where('category_id', $id)->where('status', config('constants.status_active'))->get();
        $category_name = Category::find($id)->name;

        $data = [
            'movies' => $movies,
            'categoryName' => $category_name,
        ];

        return view('pages.category', $data);
    }

    public function nation(int $id)
    {
        $movies = Movie::where('nation_id', $id)->where('status', config('constants.status_active'))->get();
        $category_name = Nation::find($id)->name;

        $data = [
            'movies' => $movies,
            'categoryName' => $category_name,
        ];

        return view('pages.category', $data);
    }

    /**
     * Trang tìm kiếm phim.
     */
    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $movies = Movie::where('name', 'like', "%$keyword%")->where('status', config('constants.status_active'))->get();

        $data = [
            'movies' => $movies,
            'categoryName' => 'Tìm kiếm',
        ];

        return view('pages.category', $data);
    }

    /**
     * Trang xem phim.
     */
    public function watch(int $id)
    {
        $movie = Movie::find($id);

        // Kiểm tra xem phim có giá không
        if (!empty($movie->price)) {
            // Nếu có kiểm tra đăng nhập hay chưa
            if (Auth::guard('web')->check()) {
                $checkMyMovie = Auth::guard('web')->user()->checkMyMovie($id);
                $error = 'Bạn chưa mua phim này. Vui lòng mua phim để xem.';
            } else {
                $checkMyMovie = false;
                $error = 'Bạn chưa mua phim này. Vui lòng đăng nhập để mua phim.';
            }
            // Nếu chưa mua phim thì trả về trang chi tiết phim tương ứng
            if (!$checkMyMovie) {
                request()->session()->flash('error', $error);
                return redirect()->route('web.movie-detail', ['id' => $id]);
            }
        }

        $data = [
            'movie' => $movie,
        ];

        return view('pages.watch', $data);
    }

    /**
     * Hàm post like phim.
     */
    public function like(int $id)
    {
        // kiểm tra phim xem đc yêu thích chưa
        $checkExists = Favorite::where([
            'movie_id' => $id,
            'customer_id' => Auth::guard('web')->id(),
        ])->exists();
        // Nếu rồi thì xóa
        if ($checkExists) {
            Favorite::where([
                'movie_id' => $id,
                'customer_id' => Auth::guard('web')->id(),
            ])->delete();
        }
        // Nếu chưa thì thêm
        else {
            Favorite::create([
                'movie_id' => $id,
                'customer_id' => Auth::guard('web')->id(),
            ]);
        }

        return redirect()->back();
    }

    /**
     * Trang danh sách phim yêu thích.
     */
    public function favorites()
    {
        //Khi đã đăng nhập, truy cập vào thuộc tính favorites của user để lấy danh sách các mục yêu thích của họ
        $favorites = Auth::guard('web')->user()->favorites;

        //Khởi tạo một mảng $movies để lưu trữ các phim yêu thích.
        $movies = [];

        //Duyệt qua từng mục yêu thích ($favorites) và lấy ra đối tượng phim ($favorite->movie) để thêm vào mảng $movies.
        foreach ($favorites as $favorite) {
            $movies[] = $favorite->movie;
        }

        $data = [
            'movies' => $movies,
            'categoryName' => 'Yêu thích',
        ];

        return view('pages.category', $data);
    }

    public function buy(int $id)
    {
        $movie = Movie::find($id);
        $customer = Auth::guard('web')->user();

        // Kiểm tra số dư trong ví còn không
        if ($customer->wallet->balance < $movie->price) {
            request()->session()->flash('error', 'Số dư trong ví không đủ để mua phim này.');

            //Sau đó, chuyển hướng người dùng quay lại trang chi tiết phim vs tham số $id của phim đó
            return redirect()->route('web.movie-detail', ['id' => $id]);
        }

        // bắt đầu giao dịch để cập nhật ví và tạo đơn hàng
        DB::transaction(function () use ($movie, $customer) {
            // Tạo 1 charge_id để nhận số tiền đã trừ của người dùng
            $charge_id = $customer->wallet->charges()->create([
                'amount' => $movie->price,
            ]);
            // Tạp 1 oder mới để  nhận đơn hàng
            $customer->orders()->create([
                'movie_id' => $movie->id,
                'wallet_charge_id' => $charge_id->id,
            ]);
            // Trừ tiền trong ví của người dùng và lưu số mới lại
            $customer->wallet->balance -= $movie->price;
            $customer->wallet->save();
        });

        // Thông báo thành công
        request()->session()->flash('success', 'Mua phim thành công.');
        // Về trang mô tả phim tương ứng
        return redirect()->route('web.movie-detail', ['id' => $id]);
    }
}