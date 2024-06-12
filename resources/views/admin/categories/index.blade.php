@extends('admin.layouts.master')

@section('content')
@component('components.breadcrumb')
@slot('li_1')
<a href="{{ route('categories.index') }}" class="btn-link">Thể loại phim</a>
@endslot
@slot('title')
Danh sách thể loại phim
@endslot
@endcomponent
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="text-sm-end">
                        <a href="{{ route('categories.create') }}"
                            class="text-white btn btn-success btn-rounded waves-effect waves-light mb-2 mr-2"><i
                                class="mdi mdi-plus mr-1"></i>Thêm thể loại phim</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" colspan="2" style="width: 70px;" class="text-center">ID</th>
                                <th scope="col" colspan="4">Tên</th>
                                <th scope="col" colspan="2" class="text-center">Công cụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                            <tr>
                                <td colspan="2" class="text-center">{{ $category->id }}</td>
                                <td colspan="4">{{ $category->name }}</td>
                                <td colspan="2" class="text-center">
                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                        <!-- <li class="list-inline-item px">
                                                <a href="{{ route('categories.show', $category->id) }}"
                                                   data-toggle="tooltip"
                                                   data-placement="top" title="Xem"><i
                                                        class="mdi mdi-eye text-primary"></i></a>
                                            </li> -->
                                        <li class="list-inline-item px">
                                            <a href="{{ route('categories.edit', $category->id) }}"
                                                data-toggle="tooltip" data-placement="top" title="Sửa"><i
                                                    class="mdi mdi-pencil text-success"></i></a>
                                        </li>
                                        <li class="list-inline-item px">
                                            <form method="post"
                                                action="{{ route('categories.destroy', $category->id) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" data-toggle="tooltip" data-placement="top"
                                                    title="Xóa" class="border-0 bg-transparent"><i
                                                        class="mdi mdi-trash-can text-danger"></i></button>
                                            </form>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $categories->links() }}
            </div>
        </div>
    </div> <!-- end col -->
</div>
@endsection