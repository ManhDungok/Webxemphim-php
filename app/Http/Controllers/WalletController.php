<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Nation;
use App\Models\WalletTopUp;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class WalletController extends Controller
{
    public function __construct()
    {
        $nations = Nation::all();
        View::share('nations', $nations);

        $categories = Category::all();
        View::share('categories', $categories);
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function show(): Renderable
    {
        // Lấy thông tin ví của người dùng
        $wallet = Auth::guard('web')->user()->wallet;

        $wallet->topUps()
            // Lọc các giao dịch 'đang chờ'
            ->where('status', config('constants.top_up_status_pending'))
            // Lọc các gd có dealine nhỏ hơn hiện tại
            ->where('deadline', '<', now())
            // Cập nhật trạng thái các gd quá hạn thành fail
            ->update(['status' => config('constants.top_up_status_failed')]);
        // TRả về trang ví
        return view('pages.wallet', ['wallet' => $wallet]);
    }

    // Tạo giao dịch nạp tiền mới
    public function top_up()
    {
        $wallet = Auth::guard('web')->user()->wallet;
        $top_up = new WalletTopUp();
        $top_up->wallet_id = $wallet->id;
        $amount = request()->amount;
        $top_up = new WalletTopUp();
        $top_up->wallet_id = $wallet->id;
        $top_up->amount = $amount;
        $top_up->deadline = now()->addMinutes(15); //15p
        $top_up->save();

        // tạo url thanh toán và chuyển hướng người dùng
        $vnp_Url = $this->generate_payment_url($amount, $top_up);

        return redirect($vnp_Url);
    }

    public function top_up_pay($id)
    {
        // tìm giao dịch theo id
        $top_up = WalletTopUp::find($id);
        // Nếu không thấy thì trả về lỗi 
        if ($top_up == NULL) {
            request()->session()->flash('error', "Lịch sử nạp tiền không tồn tại. Vui lòng thử lại.");
            return redirect()->route('web.wallet');
        }
        //  Nếu tồn tại thì chuyển hướng người dùng đến trang thanh toán
        $vnp_Url = $this->generate_payment_url($top_up->amount, $top_up);
        return redirect($vnp_Url);
    }

    public function vnpay_return()
    {
        // Lấy các tham số vnpay gửi về khi hoàn tất gd
        $vnp_SecureHash = $_GET['vnp_SecureHash'];

        // Nhập tất cả các tham số bắt đầu bằng vnp_
        $inputData = array();

        //bắt đầu từ vị trí 0 và có độ dài 4 ký tự. Do đó, nó kiểm tra xem phần bắt đầu của chuỗi khóa có phải là "vnp_" không.
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        // Loại bỏ vnp_SecureHash ra khỏi mảng 
        unset($inputData['vnp_SecureHash']);

        // Sắp xếp các tham số và tạo 1 chuỗi dữ liệu từ các tham số với các keyvalue được nối với nhau = &
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        // Tính toán giá trị hash bằng hàm hash_hmac với thuật toán 'sha512', sử dụng chuỗi dữ liệu đã tạo và một bí mật 
        // (vnp_HashSecret) được lấy từ cấu hình
        $secureHash = hash_hmac('sha512', $hashData, config('vnpay.vnp_HashSecret'));

        // so sánh hash tự tính và hash của vnp và thông báo kết quả
        if ($secureHash == $vnp_SecureHash) {
            if ($_GET['vnp_ResponseCode'] == '00') {
                request()->session()->flash('success', "Nạp tiền thành công! Số tiền " . number_format($_GET['vnp_Amount'] / 100) . " VNĐ đã được cộng vào ví của bạn.");
            } else {
                request()->session()->flash('error', "Nạp tiền thất bại! Vui lòng thử lại.");
            }
        } else {
            request()->session()->flash('error', "Giao dịch không hợp lệ");
        }
        // Dừng 5s để người dùng thấy thông báo
        sleep(5);
        // Trả về ví
        return redirect()->route('web.wallet');
    }

    public function vnpay_ipn()
    {
        // Thu thập các tham số bắt đầu bằng vnp_ vào mảng
        $inputData = array();
        $returnData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        // Lấy giá trị vnp_SecureHash ra và xóa nó vì ko sử dụng cho việc tính toán
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        // Sắp xếp các tham số và tạo 1 chuỗi dữ liệu từ các tham số với các keyvalue được nối với nhau = &

        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        // Tính toán giá trị hash bằng hàm hash_hmac với thuật toán 'sha512', sử dụng chuỗi dữ liệu đã tạo và một bí mật 
        // (vnp_HashSecret) được lấy từ cấu hình
        $secureHash = hash_hmac('sha512', $hashData, config('vnpay.vnp_HashSecret'));

        //  Chuyển đổi số tiền vnp_Amount từ đơn vị nhỏ sang đơn vị lớn bằng cách chia cho 100
        $vnp_Amount = $inputData['vnp_Amount'] / 100;

        //Lấy ID nạp tiền top_up_id từ tham số vnp_TxnRef bằng cách tách chuỗi.
        $top_up_id = explode('_', $inputData['vnp_TxnRef'])[2];

        try {
            if ($secureHash == $vnp_SecureHash) {
                $top_up = WalletTopUp::find($top_up_id);

                if ($top_up != NULL) {
                    if ($top_up->amount == $vnp_Amount) {
                        // Kiểm tra trạng thái
                        if ($top_up->status == config('constants.top_up_status_pending')) {
                            // Cập nhật trạng thái dựa trên mã phản hồi và trạng thái giao dịch (vnp_ResponseCode và vnp_TransactionStatus)
                            if ($inputData['vnp_ResponseCode'] == '00' && $inputData['vnp_TransactionStatus'] == '00') {
                                $top_up->status = config('constants.top_up_status_success');
                            } else {
                                $top_up->status = config('constants.top_up_status_failed');
                            }
                            // Xóa vnp_SecureHash và vnp_SecureHashType
                            unset($inputData['vnp_SecureHash']);
                            unset($inputData['vnp_SecureHashType']);

                            // Lưu và cập nhật số dư ví
                            $top_up->payment_info = json_encode($inputData);
                            $top_up->save();

                            $wallet = $top_up->wallet;
                            $wallet->balance += $vnp_Amount;
                            $wallet->save();

                            $returnData['RspCode'] = '00';
                            $returnData['Message'] = 'Confirm Success';
                        } else {
                            $returnData['RspCode'] = '02';
                            $returnData['Message'] = 'Order already confirmed';
                        }
                    } else {
                        $returnData['RspCode'] = '04';
                        $returnData['Message'] = 'invalid amount';
                    }
                } else {
                    $returnData['RspCode'] = '01';
                    $returnData['Message'] = 'Order not found';
                }
            } else {
                $returnData['RspCode'] = '97';
                $returnData['Message'] = 'Invalid signature';
            }
        } catch (Exception $e) {
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Unknow error';
        }
        //Trả về phản hồi dưới dạng JSON với các mã phản hồi và thông báo tương ứng
        return response()->json(json_encode($returnData));
    }

    public function vnpay_result()
    {
        request()->session()->reflash();

        return redirect()->route('web.wallet');
    }

    /**
     * @param mixed $amount
     * @param WalletTopUp $top_up
     * @return string
     */
    public function generate_payment_url(mixed $amount, WalletTopUp $top_up): string
    {
        // Khởi tạo biến
        $vnp_Amount = $amount;
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $startTime = date('YmdHis');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_TxnRef = implode('_', [$startTime, rand(1, 10000), $top_up->id]);
        // TẠo mảng với các tham số cần thiết cho vnpay
        $inputData = array(
            "vnp_Version" => "2.1.1",
            "vnp_TmnCode" => config('vnpay.vnp_TmnCode'),
            "vnp_Amount" => $vnp_Amount * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $startTime,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => "Thanh toan GD:" . str($vnp_TxnRef),
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => config('vnpay.vnp_Returnurl'),
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => date('YmdHis', strtotime('+15 minutes', strtotime($startTime)))
        );
        //Kiểm tra và thêm mã ngân hàng nếu có
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        // Sắp xếp các tham số và tạo 1 chuỗi dữ liệu từ các tham số với các keyvalue được nối với nhau = &
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Tạo url thanh toán
        $vnp_Url = config('vnpay.vnp_Url') . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }
}