@extends('admin::layout')
@section('title')
    Organization
@endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Organizations</a>
@endsection
@section('css')
    <style>
        .fc-col-header-cell-cushion {
            color: #fff !important;
        }

        .fc-event-title {
            color: #fff !important;
        }

        .fc-button-group .fc-button {
            text-transform: capitalize;
            color: #fff !important;
            border-radius: 50rem !important;
        }

        .fc-dayGridMonth-button {
            background-color: #2196f3 !important;
            border-color: #2196f3 !important;
        }

        .fc-dayGridWeek-button {
            margin-right: 0.625rem !important;
            margin-left: 0.625rem !important;
            background-color: #4a5ab9 !important;
            border-color: #4a5ab9 !important;
        }

        .fc-listWeek-button {
            border-radius: 50rem !important;
            background-color: #27a7b7 !important;
            border-color: #27a7b7 !important;
        }

        /* Custom styles for the tooltip container */
        .fc-event.custom-tooltip {
            /* Your custom styles for the event tooltip */
            /* For example: */
            position: relative;
        }

        .fc-event.custom-tooltip::after {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding: 6px 10px;
            background-color: #333;
            color: #fff;
            font-size: 14px;
            border-radius: 4px;
            white-space: nowrap;
            visibility: hidden;
            opacity: 0;
            z-index: 1;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .fc-event.custom-tooltip:hover::after {
            visibility: visible;
            opacity: 1;
        }
    </style>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script src="https://demo.interface.club/limitless/demo/template/assets/js/vendor/ui/fullcalendar/main.min.js"></script>
    <script src="https://demo.interface.club/limitless/demo/template/assets/demo/pages/fullcalendar_basic.js"></script>
@endSection

@section('content')

    <div class="card card-body1">
        <div class="navbar navbar-expand-lg navbar-light">

            <div class="col-md-2 mt-2">
                {!! Form::select('organization_id', $organizationLists, $value = request('organization_id') ?: null, [
                    'placeholder' => 'Select Organization',
                    'id' => 'organization_id',
                    'class' => 'form-control select-search organization-filter organization-filter2',
                ]) !!}
            </div>



            <div class="navbar-collapse collapse" id="navbar-second">
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <a href="#leave-tab" class="navbar-nav-link active leave" data-toggle="tab">
                            <i class="icon-menu7 mr-2"></i>
                            Leave
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#attendance" class="navbar-nav-link attendance" data-toggle="tab">
                            <i class="icon-touch mr-2"></i>
                            Attendance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#claim-request" class="navbar-nav-link" data-toggle="tab">
                            <i class="icon-stack3 mr-2"></i>
                            Claim & Request
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#payroll" class="navbar-nav-link" data-toggle="tab">
                            <i class="icon-coins mr-2"></i>
                            Payroll
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>



    <!-- Inner container -->
    <div class="d-flex align-items-stretch align-items-lg-start flex-column flex-lg-row">

        <!-- Left content -->
        <div class="tab-content w-100 order-2 order-lg-1">
            <div class="tab-pane fade active show" id="leave-tab">
            </div>

            <div class="tab-pane fade" id="attendance">
                @include('organization::organization.report.partial.attendance')
            </div>

            <div class="tab-pane fade" id="claim-request">

                <!-- Profile info -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Profile information</h6>
                    </div>

                    <div class="card-body">
                        <form action="#">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Username</label>
                                        <input type="text" value="Eugene" class="form-control">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Full name</label>
                                        <input type="text" value="Kopyov" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Address line 1</label>
                                        <input type="text" value="Ring street 12" class="form-control">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Address line 2</label>
                                        <input type="text" value="building D, flat #67" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label>City</label>
                                        <input type="text" value="Munich" class="form-control">
                                    </div>
                                    <div class="col-lg-4">
                                        <label>State/Province</label>
                                        <input type="text" value="Bayern" class="form-control">
                                    </div>
                                    <div class="col-lg-4">
                                        <label>ZIP code</label>
                                        <input type="text" value="1031" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Email</label>
                                        <input type="text" readonly="readonly" value="eugene@kopyov.com"
                                            class="form-control">
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Your country</label>
                                        <select class="custom-select">
                                            <option value="germany" selected="">Germany</option>
                                            <option value="france">France</option>
                                            <option value="spain">Spain</option>
                                            <option value="netherlands">Netherlands</option>
                                            <option value="other">...</option>
                                            <option value="uk">United Kingdom</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Phone #</label>
                                        <input type="text" value="+99-99-9999-9999" class="form-control">
                                        <span class="form-text text-muted">+99-99-9999-9999</span>
                                    </div>

                                    <div class="col-lg-6">
                                        <label>Upload profile image</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customFile">
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                        <span class="form-text text-muted">Accepted formats: gif, png, jpg. Max file
                                            size 2Mb</span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /profile info -->


                <!-- Account settings -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Account settings</h6>
                    </div>

                    <div class="card-body">
                        <form action="#">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Username</label>
                                        <input type="text" value="Kopyov" readonly="readonly" class="form-control">
                                    </div>

                                    <div class="col-lg-6">
                                        <label>Current password</label>
                                        <input type="password" value="password" readonly="readonly"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>New password</label>
                                        <input type="password" placeholder="Enter new password" class="form-control">
                                    </div>

                                    <div class="col-lg-6">
                                        <label>Repeat password</label>
                                        <input type="password" placeholder="Repeat new password" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Profile visibility</label>

                                        <label class="custom-control custom-radio mb-2">
                                            <input type="radio" name="visibility" class="custom-control-input"
                                                checked="">
                                            <span class="custom-control-label">Visible to everyone</span>
                                        </label>

                                        <label class="custom-control custom-radio mb-2">
                                            <input type="radio" name="visibility" class="custom-control-input">
                                            <span class="custom-control-label">Visible to friends only</span>
                                        </label>

                                        <label class="custom-control custom-radio mb-2">
                                            <input type="radio" name="visibility" class="custom-control-input">
                                            <span class="custom-control-label">Visible to my connections only</span>
                                        </label>

                                        <label class="custom-control custom-radio">
                                            <input type="radio" name="visibility" class="custom-control-input">
                                            <span class="custom-control-label">Visible to my colleagues only</span>
                                        </label>
                                    </div>

                                    <div class="col-lg-6">
                                        <label>Notifications</label>

                                        <label class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" checked="">
                                            <span class="custom-control-label">Password expiration notification</span>
                                        </label>

                                        <label class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" checked="">
                                            <span class="custom-control-label">New message notification</span>
                                        </label>

                                        <label class="custom-control custom-checkbox mb-2">
                                            <input type="checkbox" class="custom-control-input" checked="">
                                            <span class="custom-control-label">New task notification</span>
                                        </label>

                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label">New contact request notification</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /account settings -->

            </div>
        </div>
        <!-- /left content -->

    </div>
    <!-- /inner container -->


    <script>
        $(document).ready(function() {
            $('.leave').on('click', function(event) {
                event.preventDefault();
                var organization_id = $('#organization_id').val();
                // var leave_year_id = $('input[name="leave_year_id"]').val();

                console.log(organization_id);
                if (organization_id == '') {
                    toastr.error('Select Organization first!')
                    return false;
                }

                $('.tab-pane').each(function() {
                    $(this).removeClass('active');
                    $(this).removeClass('show');
                });

                $.ajax({
                    type: 'GET',
                    url: '{{ route('organization.getLeaveReport') }}' + '?organization_id=' +
                        organization_id,

                    success: function(data) {
                        console.log(data);
                        $('#leave-tab').html(data.view);
                        $('#leave-tab').addClass('active').addClass('show');

                    }
                });

            });

        })
    </script>
@endsection
