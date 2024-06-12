@extends('admin.layouts.master')

@section('content')
@component('components.breadcrumb')
@slot('li_1')
<a href="{{ route('movies.index') }}" class="btn-link">Phim</a>
@endslot
@slot('title')
Danh sách phim
@endslot
@endcomponent
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="text-sm-end">
                        <a href="{{ route('movies.create') }}"
                            class="text-white btn btn-success btn-rounded waves-effect waves-light mb-2 mr-2"><i
                                class="mdi mdi-plus mr-1"></i>Thêm phim</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" style="width: 70px;" class="text-center">ID</th>
                                <th scope="col">Phim</th>
                                <th scope="col">Giá mua</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col"></th>
                                <th scope="col" class="text-center">Công cụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movies as $movie)
                            <tr>
                                <td class="text-center">{{ $movie->id }}</td>
                                <td>{{ $movie->name }}</td>
                                <td>
                                    @if($movie->price)
                                    {{ number_format($movie->price) }} VNĐ
                                    @else
                                    Miễn phí
                                    @endif
                                </td>
                                <td>
                                    @if($movie->status == config('constants.status_active'))
                                    <span class="badge bg-primary">Hoạt động</span>
                                    @else
                                    <span class="badge bg-secondary">Không hoạt động</span>
                                    @endif
                                </td>
                                <td>
                                    @if($movie->trending)
                                    <span class="badge bg-success">Thịnh hành</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                        <li class="list-inline-item px">
                                            <a href="{{ route('movies.show', $movie->id) }}" data-toggle="tooltip"
                                                data-placement="top" title="Xem"><i
                                                    class="mdi mdi-eye text-secondary"></i></a>
                                        </li>
                                        <li class="list-inline-item px">
                                            <a href="{{ route('movies.edit', $movie->id) }}" data-toggle="tooltip"
                                                data-placement="top" title="Sửa"><i
                                                    class="mdi mdi-pencil text-success"></i></a>
                                        </li>
                                        @if($movie->favorites->isEmpty() && $movie->orders->isEmpty())
                                        <li class="list-inline-item px">
                                            <form method="post" action="{{ route('movies.destroy', $movie->id) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" data-toggle="tooltip" data-placement="top"
                                                    title="Xóa" class="border-0 bg-transparent"><i
                                                        class="mdi mdi-trash-can text-danger"></i></button>
                                            </form>
                                        </li>
                                        @endif
                                    </ul>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $movies->links() }}
            </div>
        </div>
    </div> <!-- end col -->
</div>
@endsection