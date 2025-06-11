@extends('admin::layout')
@section('title')Dashboard @stop

@section('breadcrum')
    <a href="{{ route('dashboard') }}" class="breadcrumb-item">Dashboard</a>
@endsection
@section('content')


    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
    @php
        $currentRoute = Request::route()->getName();
        $Route = explode('.', $currentRoute);
    @endphp

    @include('admin::employee.partial.count')

    <div class="row">
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-6">
                    @include('admin::employee.partial.announcements')
                </div>
                <div class="col-lg-6">
                    @include('admin::employee.partial.on-leave')
                </div>
                <div class="col-lg-6">
                    @include('admin::employee.partial.request_for_approval')
                </div>
                <div class="col-lg-6">
                    @include('admin::employee.partial.events')

                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="row">
                <div class="col-lg-12">
                    @include('admin::employee.partial.leave-att-summary')
                </div>
                <div class="col-lg-12">
                    @include('admin::admin.partial.reminder', ['height' => '510px'])

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @if (Module::isModuleEnabled('Onboarding'))
            <div class="col-lg-4">
                @include('admin::employee.partial.vacancy')
                {{-- @include('admin::admin.partial.reminder', ['height' => '97%']) --}}
            </div>
        @endif
        <div class="col-lg-4">
            @include('admin::employee.partial.department')
        </div>
        @if (auth()->user()->user_type == 'supervisor')
            <div class="col-lg-4">
                @include('admin::employee.partial.subordinates')
            </div>
        @endif



        <div class="col-lg-4">
            @include('admin::employee.partial.attendance-request')
        </div>

        @if (Module::isModuleEnabled('Grievance'))
            <div class="col-lg-4">
                @include('admin::employee.partial.help_desk')
                {{-- @include('admin::admin.partial.reminder', ['height' => '97%']) --}}
            </div>
        @endif
        <div class="col-lg-4">
            @include('admin::employee.partial.new_starter')
        </div>

    </div>
    <div class="row">
        <div class="col-lg-4">
            @include('admin::employee.partial.birthday')
        </div>
        @if (Module::isModuleEnabled('Poll'))
            <div class="col-lg-4">
                @include('admin::employee.partial.poll')
            </div>
        @endif
        @if (Module::isModuleEnabled('Survey'))
            <div class="col-lg-4">
                @include('admin::employee.partial.survey')
            </div>
        @endif
    </div>

    {{-- <div id="checkInModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title">Check In</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open([
                        'route' => 'leave.updateStatus',
                        'method' => 'POST',
                        'class' => 'form-horizontal updateLeaveStatusForm',
                        'role' => 'form',
                    ]) !!}
                    <div class="d-flex justify-content-center" >
                        <a href="#" class="btn btn-link btn-float text-dark">
                            <i class="icon-alarm text-danger" style="font-size: 90px"></i>
                            <span id="displayTime" style="font-size: 40px">02:36:07 PM</span>
                            <h3>10 April , 2024</h3>
                        </a>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-xl bg-success text-white mt-1">Check In</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div> --}}
@stop
@section('script')

    <script>
        $(function() {
            // window.addEventListener("beforeunload", function(e){

            // function disableBack() {
            //     console.log('asdw');
            //     window.history.forward();
            // }
            // disableBack();
            // setTimeout(disableBack(), 0);
            // window.onunload = function() {
            //     null
            // };
            // 'use strict';
            // var url = "http://127.0.0.1:8000/admin/dashboard";
            // history.pushState(null, null, url);
            // console.log('asd');
            // $(window).on('popstate', function(e) {
            //     var state = e.originalEvent.state;
            //     alert(state);
            //     if (state !== null) {
            //         //load content with ajax
            //     }
            // });



            // window.history.pushState('forward', null, './#dashboard');
            // if (window.history && window.history.pushState) {
            //     $(window).on('popstate', function() {
            //         alert('Back button was pressed.');
            //         window.history.pushState('forward12', null, './dashboard');

            //     });

            // }

        });
    </script>
@endsection
