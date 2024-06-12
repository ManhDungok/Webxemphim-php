@extends('admin.layouts.master')

@section('content')
@component('components.breadcrumb')
@slot('li_1')
Giao dịch
@endslot
@slot('title')
Giao dịch
@endslot
@endcomponent
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="text-sm-end">
                        <a href="{{ route('transactions.statics') }}" class="btn-link">Thống kê</a>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="text-sm-end">
                        <h1>Tổng doanh thu: {{number_format($total_income)}} VNĐ</h1>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" style="width: 70px;" class="text-center">ID</th>
                                <th scope="col">Khách hàng</th>
                                <th scope="col">Tên phim</th>
                                <th scope="col">Số tiền</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Ngày giờ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($top_ups as $top_up)

                            <tr>
                                <td class="text-center">{{ $top_up->id }}</td>
                                <td>{{ $top_up->wallet->customer->name }}</td>
                                <td>
                                    {{ number_format($top_up->amount) }} VNĐ
                                </td>
                                <td>
                                    @if($top_up->status == config('constants.top_up_status_pending'))
                                    <span class="badge bg-warning">Chờ thanh toán</span>
                                    @elseif($top_up->status == config('constants.top_up_status_success'))
                                    <span class="badge bg-success">Thành công</span>
                                    @else
                                    <span class="badge bg-danger">Thất bại</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $top_up->created_at }}
                                </td>
                                <td class="text-center">
                                    @if($top_up->payment_info)
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#top_up_payment_info_{{$top_up->id}}">
                                        Xem
                                    </button>
                                    <div class="modal fade" id="top_up_payment_info_{{$top_up->id}}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-sm-start">
                                                    @foreach(json_decode($top_up->payment_info) as $key => $value)
                                                    {{$key}}: {{$value}} <br>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $top_ups->links() }}
            </div>
        </div>
    </div> <!-- end col -->
</div>
@endsection