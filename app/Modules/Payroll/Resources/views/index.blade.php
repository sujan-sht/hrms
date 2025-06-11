@extends('admin::layout')

@section('title')
    {{ $title }}s
@endsection

@section('breadcrum')
<a href="" class="breadcrumb-item">Attendance</a>
<a class="breadcrumb-item active">List</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@endsection

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('payroll.name') !!}
    </p>
@endsection
