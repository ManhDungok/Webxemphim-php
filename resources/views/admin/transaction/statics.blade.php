@extends('admin.layouts.master')

@section('content')
@component('components.breadcrumb')
@slot('li_1')
Thống kê
@endslot
@slot('title')
Thống kê
@endslot
@endcomponent
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="text-sm-end">
                        <h1>Tổng doanh thu: {{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</h1>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 70px;" class="text-center">STT</th>
                                <th>Tên phim</th>
                                <th>Số lần mua</th>
                                <th>Giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php ($stt = 1)
                            @foreach ($movies as $movie)
                            <tr>
                                <td class="text-center">{{ $stt++ }}</td>
                                <td>{{ $movie->name }}</td>
                                <td>{{ $movie->orders_count }}</td>
                                <td>{{ $movie->price_label }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div>
@endsection