@extends('admin::layout')
@section('title') Employee Archive @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Former Employee</a>
@stop
@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/validation/uploadEmployee.js') }}"></script>
    <script src="{{ asset('admin/typeahead.bundle.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
@stop

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-danger border-danger border-3 rounded-round p-2"></i>
            </a>

            <div class="media-body text-center text-md-left">
                <h6 class="media-title text-danger font-weight-semibold">List of Former Employee</h6>
                All the Former Employee Information will be listed below.

            </div>


            <a href="{{ route('employee.index') }}" class="btn text-light bg-success mr-1" data-popup="tooltip"
                data-original-title="Back to employee directory" data-placement="top"><i class="icon-history"></i></a>
            <div class="list-icons mt-2">
                <div class="dropdown position-static">
                    <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-more2"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal_default_import">
                            <i class="icon-file-excel text-success"></i> Import
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('employee::employee.partial.uploadarchived')
    <div class="row">
        @if ($employments->total() != 0)
            @foreach ($employments as $key => $value)
                @php
                    if ($value->profile_pic != '') {
                        $imagePath = asset($value->file_full_path) . '/profile_pic/' . $value->profile_pic;
                    } else {
                        $imagePath = asset('admin/default.png');
                    }
                @endphp

                <div class="col-xl-3 col-sm-6">
                    <div class="card bg-secondary text-white"
                        style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                        <div class="card-body text-center">
                            <div class="card-img-actions d-inline-block mb-3">
                                <img class="img-fluid rounded-circle" src="{{ $imagePath }}" width="170"
                                    height="170" alt="">
                                <div class="card-img-actions-overlay card-img rounded-circle">
                                </div>
                            </div>

                            <h6 class="font-weight-semibold mb-0">
                                {{ $value->first_name . ' ' . $value->middle_name . ' ' . $value->last_name }}</h6>
                            <span class="d-block opacity-75">{{ optional($value->designation)->title }}</span>

                            <ul class="list-inline list-inline-condensed mb-0 mt-2">
                                <li class="list-inline-item">
                                <li class="list-inline-item"><a href="{{ route('employee.view', $value->id) }}"
                                        class="btn btn-outline-primary btn-icon text-light border-1" data-popup="tooltip"
                                        data-placement="bottom" data-original-title="View Employee">
                                        <i class="icon-eye"></i></a>
                                </li>
                                <a data-toggle="modal" data-target="#modal_theme_warning_status"
                                    class="btn btn-outline-success btn-icon text-light border-1 status_employee"
                                    link="{{ route('employee.update.status.archive.user', $value->id) }}"
                                    data-popup="tooltip" data-placement="bottom"
                                    data-original-title="Move To Active State"><i class="icon-thumbs-up3"></i></a>
                                </li>
                            </ul>
                        </div>
                        @php
                            if ($value->status == '1') {
                                $status = 'Active';
                                $color = 'bg-success';
                            } else {
                                $status = 'InActive';
                                $color = 'bg-danger';
                            }
                        @endphp
                        <div class="ribbon-container">
                            <div class="ribbon {{ $color }}">
                                <a class="text-light" href="" data-popup="tooltip"
                                    data-original-title="Employee Status" data-placement="bottom">{{ $status }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <span style="margin: 5px;float: right;">
        @if ($employments->total() != 0)
            {{ $employments->appends(request()->all())->links() }}
        @endif
    </span>



    <!-- Warning modal -->
    <div id="modal_theme_warning_status" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <center>
                        <i class="icon-alert text-danger icon-3x"></i>
                    </center>
                    <br>
                    <center>
                        <h2>Are You Sure, You Want To Move This Employee Into Active State ?</h2>
                        <a class="btn btn-success get_link" href="">Yes, Move It!</a>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </center>
                </div>
            </div>
        </div>
    </div>
    <!-- /warning modal -->


    <script type="text/javascript">
        $('document').ready(function() {
            $('.status_employee').on('click', function() {
                var link = $(this).attr('link');
                $('.get_link').attr('href', link);
            });
        });
    </script>

@endsection
