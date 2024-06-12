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

        $all_top_ups = WalletTopUp::all()->where('status', config('constants.top_up_status_success'));
        foreach($all_top_ups as $top_up){
            $total_income += $top_up->amount;
        }
        $top_ups = WalletTopUp::paginate(config('view.default_pagination'));

        $data = [
            'total_income' => $total_income,
            'top_ups' => $top_ups,
        ];

        return view('admin.transaction.index', $data);
    }
    public function showMovies()
    {
        $movies = Movie::withCount('orders')->get();
        $totalRevenue = 0;

        foreach ($movies as $movie) {
            $totalRevenue += $movie->orders_count * $movie->price;
        }
    
        View::share('totalRevenue', $totalRevenue);
        return view('admin.transaction.statics', compact('movies'));
    }
}