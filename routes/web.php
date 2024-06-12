<?php

use App\Http\Controllers\Admin\NationController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthLoginController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('admin')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/users/view-change-password/{user}', [UserController::class, 'viewChangePassword'])->name('users.view-change-password');
        Route::post('/users/change-password/{user}', [UserController::class, 'changePassword'])->name('users.change-password');

        Route::resource('categories', CategoryController::class);
        Route::resource('nations', NationController::class);
        Route::resource('movies', AdminMovieController::class);

        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transaction/statics', [TransactionController::class, 'showMovies'])->name('transactions.statics');

    });
    require __DIR__.'/auth.php';
});

// Route::namespace('App\Http\Controllers')->group(function () {
//     // Route::get('/',[IndexController::class, 'homepage'])->name('homepage');
//     Route::get('/yeu-thich', [IndexController::class, 'favorite'])->name('favorite');
//     Route::get('/xu-huong', [IndexController::class, 'trending'])->name('trending');
//     Route::get('/sap-ra-mat', [IndexController::class, 'comming'])->name('comming');
//     Route::get('/phim', [IndexController::class, 'movie'])->name('movie');
//     Route::get('/xem-phim', [IndexController::class, 'watch'])->name('watch');
// });

Route::get('login', [AuthLoginController::class, 'login'])->name('web.login');
Route::post('login', [AuthLoginController::class, 'store'])->name('web.post-login');
Route::get('register', [AuthLoginController::class, 'register'])->name('web.register');
Route::post('register', [AuthLoginController::class, 'postRegister'])->name('web.post-register');

// Route để chuyển hướng người dùng đến Google
Route::get('login/google', [AuthLoginController::class, 'redirectToProvider'])->name('web.login-google');
// Route để xử lý phản hồi từ Google
Route::get('login/google/callback', [AuthLoginController::class, 'handleProviderCallback']);
Route::post('logout-web', [AuthLoginController::class, 'destroy'])->name('web.logout');

Route::get('/', [MovieController::class, 'index'])->name('web.home');
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('web.movie-detail');
Route::get('/categories/{id}/movies', [MovieController::class, 'category'])->name('web.movie-category');
Route::get('/nations/{id}/movies', [MovieController::class, 'nation'])->name('web.movie-nation');
Route::get('/search', [MovieController::class, 'search'])->name('web.movie-search');
Route::get('/movies/{id}/watch', [MovieController::class, 'watch'])->name('web.movie-watch');

Route::middleware(['auth_customer'])->group(function () {
    Route::post('/movies/{id}/favorite', [MovieController::class, 'like'])->name('web.movie-like');
    Route::get('/my_favorite', [MovieController::class, 'favorites'])->name('web.favorites');
    Route::get('/wallet', [WalletController::class, 'show'])->name('web.wallet');
    Route::post('/wallet/top_up', [WalletController::class, 'top_up'])->name('web.wallet-top-up');
    Route::get('/wallet/top_up/{id}', [WalletController::class, 'top_up_pay'])->name('web.wallet-top-up-pay');
    Route::post('/movies/{id}/buy', [MovieController::class, 'buy'])->name('web.movie-buy');
    Route::get('/profile_settings', [ProfileController::class, 'profile_settings'])->name('web.profile-settings');
    Route::post('/update_profile', [ProfileController::class, 'update_profile'])->name('web.update-profile');
});

Route::get('vnpay_return', [WalletController::class, 'vnpay_return'])->name('web.vnpay-return');
Route::get('vnpay_ipn', [WalletController::class, 'vnpay_ipn'])->name('web.vnpay-ipn');

// Auth::routes();