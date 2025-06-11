    @extends('admin::layout')
    @section('title')Province Setup @stop
    @section('breadcrum')
        <a href="{{ route('province-setup.index') }}" class="breadcrumb-item">Province  Setup </a>
        <a class="breadcrumb-item active"> Add Province </a>
    @endsection


    @section('script')


    @stop

    @section('content')
        <!-- Form inputs -->

        {!! Form::open([
            'route' => 'province-setup.store',
            'method' => 'POST',
            'class' => 'form-horizontal',
            'role' => 'form',
            'id' => 'province_submit',
        ]) !!}

        @include('setting::province-setup.partial.action', ['btnType' => 'Save'])

        {!! Form::close() !!}




    @endsection
