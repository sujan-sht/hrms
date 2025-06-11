@extends('admin::layout')

@section('title')
    {{ $title }}s
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active">{{ $title }}s</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


    {{-- @include('employee::employee.partial.directory-advance-filter') --}}

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of {{ $title }}s</h6>
                All the {{ $title }}s Information will be listed below.
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        {{-- <div class="col-lg-2"> --}}
                        <div class="col-lg-9">
                        </div>
                        <div class="col-lg-3">
                            <div class="row">
                                <label class="col-form-label col-lg-6">Result Per Page :</label>
                                <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select(
                                            'sortBy',
                                            [10 => 10, 20 => 20, 100 => 100],
                                            request()->get('sortBy') ? request()->get('sortBy') : 20,
                                            [
                                                'class' => 'form-control sortBy',
                                                'placeholder' => 'Select',
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped mb-0">
                            <thead>
                                <tr class="text-white">
                                    <th>S.N</th>
                                    <th>Employee Name</th>
                                    <th>Status</th>
                                   <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($changes as $change)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ @$change->employee->full_name }}</td>
                                        <td>{{ @$change->status }}</td>
                                        <td class="d-flex">
                                            <a class="btn btn-outline-primary btn-icon mx-1"
                                                href="{{ route('request-change.view', $change->employee_id) }}" data-popup="tooltip"
                                                data-placement="top" data-original-title="Request Changes Detail">
                                                <i class="icon-eye"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9">No record found.</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <ul class="pagination pagination-rounded justify-content-end mb-3">
                @if ($changes->count() != 0)
                    {{ $changes->links() }}
                @endif
            </ul>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $('document').ready(function() {
            $('.branch-filter').on('change', function() {
                var branchId = $(this).val();
                var selectedUnitId = "{{ @request()->get('unit_id') }}" ?? null;
                $.ajax({
                    url: "{{ route('filter-branch-unit') }}",
                    type: "get",
                    data: {
                        branchId: branchId
                    },
                    success: function(response) {
                        var option = '';
                        option += '<option value="">Select Unit</option>';
                        $.each(response, function(index, value) {
                            option +=
                                `<option value="${index}" ${selectedUnitId==index ? 'selected':''}>${value}</option>`;
                        });
                        $('.unit-filter').html(option);
                    }
                });
            });
            $('.branch-filter').change();
            $(document).on('change', '.sortBy', function() {
                var value = $(this).val();
                var search_form = $('#directorySearchForm').serialize() + '&sortBy=' + value;
                var url = window.location.origin + "" + window.location.pathname + '?' + search_form;
                window.location = url;
            });


            $(document).on('click', '.updateUser', function() {
                $('.username').val($(this).data('username'))
                $('.id').val($(this).data('id'))

                $('.check_available, .create_user_access').on('click', function(event) {
                    alert('directory')
                    var username = $('#username').val();
                    var user_exist = $('.user_exist').val();
                    var userid = $('.id').val();

                    if (username == '') {
                        $('#username').focus();
                        $('#username').css('border-color', 'red');
                        $('.error_username').html(
                            '<i class="icon-thumbs-down3 mr-1"></i> Please Set Username.');
                        $('.error_username').addClass('text-danger');
                        event.preventDefault();
                        return false;
                    }

                    $.ajax({
                        type: 'GET',
                        url: 'checkAvailabilityOthers',
                        data: {
                            username: username,
                            userid: userid
                        },
                        async: false,
                        success: function(data) {

                            if (data == 1) {
                                $('#username').css('border-color', 'red');
                                $('.error_username').html(
                                    '<i class="icon-thumbs-down3 mr-1"></i> Username Already Exists.'
                                );
                                $('.error_username').removeClass('text-success');
                                $('.error_username').addClass('text-danger');
                                $('.user_exist').val('1');
                                $('#username').focus();
                                event.preventDefault();
                            } else {
                                $('#username').css('border-color', 'green');
                                $('.error_username').html(
                                    '<i class="icon-thumbs-up3 mr-1"></i>User Available.'
                                );
                                $('.error_username').removeClass('text-danger');
                                $('.error_username').addClass('text-success');
                                $('.user_exist').val('0');
                            }

                        }
                    });
                });
            })

            $(document).on('change', '#see_password', function() {
                if (this.checked) {
                    $('.password').attr('type', 'text')
                } else {
                    $('.password').attr('type', 'password')
                }
            })

            //multiple leaves update status
            $('#checkAll').checkAll();
            $('.checkItem').on('click', function() {
                var anyChecked = $('.checkItem:checked').length > 0;
                $('.bulkUpdateStatusDiv').toggleClass('d-none', !anyChecked);
            });

            $('.checkAll').on('click', function() {
                var anyChecked = $('.checkAll:checked').length > 0;
                $('.bulkUpdateStatusDiv').toggleClass('d-none', !anyChecked);
            });

            $(document).on("click", '.bulkUpdateStatus', function() {
                // $("#bulkUpdateStatus").html('');
                var request_ids = $("input[name='employee_ids[]']:checked").map(function() {
                    return $(this).val();
                }).get();
                var request_ids_string = JSON.stringify(request_ids);

                $('#employeeIds').val(request_ids_string)
                $('#bulkUpdateStatus').modal('show')
            });
            //
        });
    </script>
    <!-- Select2 JS -->
    <script src="{{ asset('admin/assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/pages/form-advanced.init.js') }}"></script>


    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection
