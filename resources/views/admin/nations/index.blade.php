@extends('admin.layouts.master')

@section('content')
@component('components.breadcrumb')
@slot('li_1')
<a href="{{ route('nations.index') }}" class="btn-link">Quốc gia</a>
@endslot
@slot('title')
Danh sách quốc gia
@endslot
@endcomponent
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="text-sm-end">
                        <a href="{{ route('nations.create') }}"
                            class="text-white btn btn-success btn-rounded waves-effect waves-light mb-2 mr-2"><i
                                class="mdi mdi-plus mr-1"></i>Thêm quốc gia</a>
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
                            @foreach ($nations as $nation)
                            <tr>
                                <td colspan="2" class="text-center">{{ $nation->id }}</td>
                                <td colspan="4">{{ $nation->name }}</td>
                                <td colspan="2" class="text-center">
                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                        <!-- <li class="list-inline-item px">
                                                <a href="{{ route('nations.show', $nation->id) }}"
                                                   data-toggle="tooltip"
                                                   data-placement="top" title="Xem"><i
                                                        class="mdi mdi-eye text-secondary"></i></a>
                                            </li> -->
                                        <li class="list-inline-item px">
                                            <a href="{{ route('nations.edit', $nation->id) }}" data-toggle="tooltip"
                                                data-placement="top" title="Sửa"><i
                                                    class="mdi mdi-pencil text-success"></i></a>
                                        </li>
                                        <li class="list-inline-item px">
                                            <form method="post" action="{{ route('nations.destroy', $nation->id) }}">
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

                {{ $nations->links() }}
            </div>
        </div>
    </div> <!-- end col -->
</div>
@endsection