@extends('admin::layout')

@section('title')
    Employee View
@endsection

@section('breadcrum')
    <a href="{{ route('employee.index') }}" class="breadcrumb-item">Employees</a>
    <a class="breadcrumb-item active">View</a>
@endsection

@section('content')
    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
    <div class="card">
        <div class="card-body">
            <div class="row tab-content">
                <div class="col-md-12">
                    <div class="card">

                        @if (isset($changes))
                            <div class="card-body">
                                <div class="col-12">
                                    <legend class="text-uppercase font-size-sm font-weight-bold">Changes Request for Employee ({{$employeeModel->first_name.' '.$employeeModel->last_name}})</legend>
                                </div>
                                <!-- Tabs -->
                                <ul class="nav nav-tabs" id="changeTabs">
                                    <li class="nav-item">
                                        <a class="nav-link position-relative active" id="approved-tab" data-bs-toggle="tab" href="#personalChanges">
                                            Personal Details
                                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill">
                                                {{ $changes->where('entity',null)->where('status','pending')->count() }}
                                            </span>
                                        </a>
                                    </li>
                                    @foreach ($detail_changes as $entity => $item)
                                        <li class="nav-item position-relative">
                                            <a class="nav-link {{ request('tab') == "{$entity}Changes" ? 'active' : '' }}" id="{{ $entity }}-tab" data-bs-toggle="tab" href="#{{ $entity }}Changes">
                                                {{ $entity }} Changes
                                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill">
                                                    {{ $item->where('status','pending')->count() }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <!-- Tab Content -->
                                <div class="tab-content mt-3">
                                    <!-- Approved Changes Tab -->
                                    <div class="tab-pane fade show active" id="personalChanges">
                                        <div class="col-1 text-center">
                                            <a href="javascript:void(0);" id="p_approved">Approved Changes</a>
                                        </div>
                                        <div class="" id="personalpending">
                                            @if ($changes->where('status', 'pending')->count() > 0)
                                                @foreach ($changes->where('status', 'pending') as $change)
                                                    <div class="mb-2">
                                                        <div class="mt-2" id="pendingChangeTable_{{ $change->id }}">
                                                            <div class="row mb-3">
                                                                <div class="col-8">
                                                                    <legend class="text-uppercase font-size-sm font-weight-bold">Requested Personal Details</legend>
                                                                </div>
                                                                @if ($change != "approved")
                                                                    <div class="col-1 text-center">
                                                                        <a href="{{ route('change-approval', ['status' => "0", 'id' => $change->id]) }}" class="btn btn-sm btn-primary rounded-pill">Decline</a>
                                                                    </div>
                                                                    <div class="col-1 text-center">
                                                                        <a href="{{ route('change-approval', ['status' => "1", 'id' => $change->id]) }}" class="btn btn-sm btn-success rounded-pill">Approve</a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            @include('employee::employee.partial.change-request', ['changes' => $change])
                                                            <p>Requested By
                                                                {{$employeeModel->first_name.' '.$employeeModel->last_name. ' '}}
                                                                on {{ date_converter()->eng_to_nep_convert(date("Y-m-d", strtotime($change->change_date))) . ' '}} {{date("h:i A", strtotime(@$change->change_date))}}.
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p>No pending changes available.</p>
                                            @endif
                                        </div>
                                        <div class="" id="personalapproved" style="display: none;">
                                            @if ($changes->where('status', 'approved')->count() > 0)
                                                <div class="row mb-3">
                                                    <div class="col-8">
                                                        <legend class="text-uppercase font-size-sm font-weight-bold">Previous Approved Personal Details</legend>
                                                    </div>
                                                </div>
                                                @foreach ($changes->where('status', 'approved') as $change)
                                                    <div class="mb-2">
                                                        <div class="mt-2" id="pendingChangeTable_{{ $change->id }}">
                                                            @include('employee::employee.partial.change-request', ['changes' => $change])
                                                            <p>
                                                                Requested By
                                                                {{$employeeModel->first_name.' '.$employeeModel->last_name. ' '}}
                                                                on {{ date_converter()->eng_to_nep_convert(date("Y-m-d", strtotime($change->change_date))) }} {{date("h:i A", strtotime(@$change->change_date))}}
                                                                approved by
                                                                {{@$change->approved_info->first_name.' '.@$change->approved_info->last_name}}
                                                                at {{ date_converter()->eng_to_nep_convert(date("Y-m-d", strtotime($change->approved_date_date))) }} {{date("h:i A", strtotime(@$change->approved_date_date))}}.
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p>No Approved changes available.</p>
                                            @endif
                                        </div>

                                    </div>

                                    @foreach ($detail_changes as $key => $details)
                                        <div class="tab-pane fade show {{ request('tab') == "{$key}Changes" ? 'active' : '' }}" id="{{ $key }}Changes">
                                            <div class="col-1 text-center">
                                                <a href="javascript:void(0);" class="toggleChanges" data-target="{{ $key }}">Approved Changes</a>
                                            </div>
                                            <div class="" id="{{$key}}pending">
                                                @foreach ($details->where('status','pending') as $detail)
                                                    <div class="row mb-3">
                                                        <div class="col-8">
                                                            <legend class="text-uppercase font-size-sm font-weight-bold">Requested {{$key}}</legend>
                                                        </div>
                                                        @if ($detail != "approved")
                                                            <div class="col-1 text-center">
                                                                <a href="{{ route('change-approval', ['status' => "0", 'id' => $detail->id]) }}" class="btn btn-sm btn-primary rounded-pill">Decline</a>
                                                            </div>
                                                            <div class="col-1 text-center">
                                                                <a href="{{ route('change-approval', ['status' => "1", 'id' => $detail->id]) }}" class="btn btn-sm btn-success rounded-pill">Approve</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-bordered">
                                                            <thead>
                                                                <tr class="text-light btn-slate">
                                                                    <th>Field</th>
                                                                    <th>Previous Status</th>
                                                                    <th>New Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $oldEntity = $detail->oldEntity;
                                                                    $newEntity = $detail->newEntity;
                                                                @endphp
                                                                @if ($newEntity)
                                                                    @if ($detail->entity == 'FamilyDetail')
                                                                        @include('employee::employee.partial.change-familydetail', ['changes' => $change])
                                                                    @endif

                                                                    @if ($detail->entity == 'EducationDetail')
                                                                        @include('employee::employee.partial.change-education', ['changes' => $change])
                                                                    @endif

                                                                    @if ($detail->entity == 'AwardDetail')
                                                                        @include('employee::employee.partial.change-award', ['changes' => $change])
                                                                    @endif

                                                                    @if ($detail->entity == 'SkillDetail')
                                                                        @include('employee::employee.partial.change-skill', ['changes' => $change])
                                                                    @endif

                                                                    @if ($detail->entity == 'ResearchAndPublicationDetail')
                                                                        @include('employee::employee.partial.change-research', ['changes' => $change])
                                                                    @endif

                                                                    @if ($detail->entity == 'PreviousJobDetail')
                                                                        @include('employee::employee.partial.change-previous-job', ['changes' => $change])
                                                                    @endif
                                                                @else
                                                                    <tr>
                                                                        <td colspan="4" class="text-center">No changes found.</td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                        <p>Requested By
                                                            {{$employeeModel->first_name.' '.$employeeModel->last_name. ' '}}
                                                            on {{ date_converter()->eng_to_nep_convert(date("Y-m-d", strtotime($detail->change_date))) . ' ' }} {{date("h:i A", strtotime(@$detail->change_date))}}.
                                                        </p>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="" id="{{$key}}approved" style="display: none;">
                                                @if ($details->where('status','approved')->count() > 0 )
                                                    <div class="row mb-3">
                                                        <div class="col-8">
                                                            <legend class="text-uppercase font-size-sm font-weight-bold btn-sm btn-primary">Previous {{$key}} Changes Approved</legend>
                                                        </div>
                                                    </div>
                                                    @foreach ($details->where('status','approved') as $detail)
                                                        <div class="table-responsive">
                                                            <table class="table table-hover table-bordered">
                                                                <thead>
                                                                    <tr class="text-light btn-slate">
                                                                        <th>Field</th>
                                                                        <th>Previous Status</th>
                                                                        <th>New Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                        $oldEntity = $detail->oldEntity;
                                                                        $newEntity = $detail->newEntity;
                                                                    @endphp
                                                                    @if ($oldEntity && $newEntity)
                                                                        @if ($detail->entity == 'BankDetail')
                                                                            @include('employee::employee.partial.change-bank', ['changes' => $change])
                                                                        @endif

                                                                        @if ($detail->entity == 'FamilyDetail')
                                                                            @include('employee::employee.partial.change-familydetail', ['changes' => $change])
                                                                        @endif
                                                                    @else
                                                                        <tr>
                                                                            <td colspan="4" class="text-center">No changes found.</td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                            <p>Requested By
                                                                {{$employeeModel->first_name.' '.$employeeModel->last_name. ' '}}
                                                                on {{ date_converter()->eng_to_nep_convert(date("Y-m-d", strtotime($detail->change_date))).' ' }} {{date("h:i A", strtotime(@$detail->change_date))}}
                                                                approved by
                                                                {{@$detail->approved_info->first_name.' '.@$detail->approved_info->last_name}}
                                                                at {{ date_converter()->eng_to_nep_convert(date("Y-m-d", strtotime($detail->approved_date_date))) }} {{date("h:i A", strtotime(@$detail->approbed_date))}}.
                                                            </p>

                                                        </div>
                                                    @endforeach
                                                @else
                                                    <p>No Approved changes available.</p>
                                                @endif
                                            </div>


                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Add this in your layout if Bootstrap is not included -->
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

@endsection

@push('custom_script')

<script>
    $(document).ready(function(){
        $("#p_approved").on('click', function(){
            console.log("Toggle clicked");
            $("#personalpending").toggle();
            $("#personalapproved").toggle();

            if ($("#personalapproved").is(":visible")) {
                $(this).text("Pending Changes");
            } else {
                $(this).text("Approved Changes");
            }
        });

        $(".toggleChanges").on('click', function() {
            var targetKey = $(this).data('target');
            $("#" + targetKey + "pending").toggle();
            $("#" + targetKey + "approved").toggle();

            if ($("#" + targetKey + "approved").is(":visible")) {
                $(this).text("Pending Changes");
            } else {
                $(this).text("Approved Changes");
            }
        });
    });
</script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/editors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/editor_ckeditor_default.js') }}"></script>

    {{-- <script src="https://cdn.tiny.cloud/1/cjrqkjizx7e1ld0p8kcygaj4cvzc6drni6o4xl298c5hl9l1/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script> --}}
    <script>
        $(document).ready(function() {
            $('.select-search1').select2();
            // tinymce.init({
            //     selector: 'textarea.basicTinymce',
            //     plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            //     toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            //     height: '250',
            //     width: '100%'
            // });

            $('.createmode').click(function() {
                var name = $(this).data('name');
                var card_form = $('.create' + name + 'Detail');

                $(this).parents('.col-md-12').removeClass().addClass('col-md-8');
                card_form.parent().removeClass('d-none');
                $(this).addClass('d-none');

                card_form.find('.form-group-feedback').each(function() {
                    $(this).find('.border-success').removeClass('border-success');
                    $(this).find('.form-control-feedback').remove();
                })
            })

            $('.go-back').click(function() {
                var that = $(this);
                toggleCreateBtn(that)
            })

        });
    </script>
@endpush

@section('popupScript')
    <script>
        function toggleCreateBtn(that) {
            that.closest('.row').find('.col-md-8').removeClass().addClass('col-md-12');
            that.closest('.row').find('.col-md-4').addClass('d-none');
            that.closest('.row').find('.createmode').removeClass('d-none');
        }

        function editModal(that) {
            that.closest('.row').find('.col-md-12').removeClass().addClass('col-md-8');
            that.closest('.row').find('.col-md-4').removeClass('d-none');
            that.closest('.row').find('.createmode').addClass('d-none');
        }

        function viewModal(that) {
            that.closest('.row').find('.col-md-12').removeClass().addClass('col-md-8');
            that.closest('.row').find('.col-md-4').removeClass('d-none');
            that.closest('.row').find('.createmode').addClass('d-none');
        }
    </script>
@endsection

