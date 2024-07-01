@extends('admin.layouts.master')

@section('content')
@component('components.breadcrumb')
@slot('li_1')
Khách hàng
@endslot
@slot('title')
Danh sách khách hàng
@endslot
@endcomponent
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('users.index') }}" class="row mb-2">
                    <div class="col-sm-3">
                        <div class="search-box mr-2 mb-2 d-inline-block">
                            <div class="position-relative">
                                <input type="text" name="search" class="form-control" placeholder="Nhập họ và tên...">
                                <i class="bx bx-search-alt search-icon"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-success waves-effect waves-light">
                            <i class="bx bx-search-alt search-icon font-size-16 align-middle mr-2"></i> Tim kiem
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-centered table-nowrap">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 70px;" class="text-center">STT</th>
                                <th>Họ và tên</th>
                                <th>Email</th>
                                <th>Tài khoản</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php ($stt = 1)
                            @foreach ($users as $user)
                            <tr>
                                <td class="text-center">{{ $stt++ }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->username }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $users->links() }}
            </div>
        </div>
    </div> <!-- end col -->
</div>
@endsection