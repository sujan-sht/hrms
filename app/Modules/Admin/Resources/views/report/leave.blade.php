@extends('admin::layout')
@section('title') Leave @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Leaves</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

@include('admin::report.partial.leave-filter', ['route' => route('leave.index')])

<div class="card card-body">
    <section class="leave-detail">
        <!-- Leave Summary -->
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">
                    Leave Summary
                </h6>
                <div class="header-elements">
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-2">

                    <div class="col-lg-3">
                        <div class="row">
                            <div class="col-lg-12 mb-2">

                                <table class="table table-bordered">
                                    <tr>

                                        <td>Full Leave</td>
                                        <td>{{ $count['full_leave'] }}</td>
                                    </tr>
                                    <tr>

                                        <td>Half Leave</td>
                                        <td>{{ $count['half_leave'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td>{{ $count['total_leave'] }}</td>


                                    </tr>
                                </table>
                            </div>

                            <div class="col-lg-12">

                                <table class="table table-bordered">
                                    <tr>

                                        <td>First Half Leave</td>
                                        <td>{{ $count['first_half_leave'] }}</td>
                                    </tr>
                                    <tr>

                                        <td>Second Half Leave</td>
                                        <td>{{ $count['second_half_leave'] }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header header-elements-inline">
                                <h6 class="card-title">Leave Type</h6>
                            </div>

                            <div class="table-responsive1">
                                <table class="table table-striped table-hover text-nowrap">
                                    <tbody>
                                        @foreach ($count_leave_types as $leaveTypeKey => $leaveType)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="mr-3">
                                                            <a href="#"
                                                                class="btn btn-primary rounded-pill btn-icon btn-sm">
                                                                <span
                                                                    class="letter-icon">{{ substr($leaveTypeList[$leaveTypeKey], 0, 1) }}</span>
                                                            </a>
                                                        </div>
                                                        <div>
                                                            <a href="#"
                                                                class="text-body font-weight-semibold letter-icon-title">{{ $leaveTypeList[$leaveTypeKey] }}</a>
                                                            {{-- <div class="text-muted font-size-sm">CL</div> --}}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{-- <span class="badge badge-success font-size-sm">Active</span> --}}
                                                </td>
                                                <td>

                                                    <span
                                                        class="badge badge-info badge-pill font-size-sm">{{ $leaveType }}</span>
                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                </div>



                <div class="row">


                </div>
            </div>
        </div>
        <!-- /Leave Summary -->

    </section>

</div>

@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script>
    $(document).ready(function() {

    });
</script>
@endSection
