@extends('admin::layout')
@section('title') Manpower Requisition Forms @endSection
@section('breadcrum')
<a href="{{ route('mrf.index') }}" class="breadcrumb-item">Manpower Requisition Forms</a>
<a class="breadcrumb-item active">View</a>
@stop

@section('content')

{!! Form::model($mrfModel, [
    'method' => 'PUT',
    'route' => ['mrf.update', $mrfModel->id],
    'class' => 'form-horizontal',
    'id' => 'mrfFormSubmit',
    'role' => 'form',
    'files' => true,
]) !!}

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Organization :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('organization_id', $organizationList, null, [
                                        'class' => 'form-control select-search',
                                        'disabled',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Reference Number :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('reference_number', null, [
                                        'placeholder' => 'e.g: 54321',
                                        'class' => 'form-control numeric',
                                        'disabled',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Title :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('title', null, [
                                        'placeholder' => 'e.g: Vacancy for Developer',
                                        'class' => 'form-control',
                                        'disabled',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Last Submission Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    @php
                                        if (setting('calendar_type') == 'BS') {
                                            $classData = 'form-control nepali-calendar';
                                            $dateData = date_converter()->eng_to_nep_convert($mrfModel['end_date']);
                                        } else {
                                            $classData = 'form-control daterange-single';
                                            $dateData = $mrfModel['end_date'];
                                        }
                                    @endphp
                                    {!! Form::text('end_date', $dateData, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => $classData,
                                        'autocomplete' => 'off',
                                        'disabled',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Sub-Function :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('department', $departmentList, null, [
                                        'placeholder' => 'Select Sub-Function',
                                        'class' => 'form-control select-search',
                                        'disabled',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Designation :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('designation', $designationList, null, [
                                        'placeholder' => 'Select Designation',
                                        'class' => 'form-control select-search',
                                        'disabled',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">MRF Type :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('type', $mrfTypeList, null, ['class' => 'form-control select-search', 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Job Description:</label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                {!! $mrfModel->description !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Job Specification:</label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                {!! $mrfModel->specification !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Other Detail</legend>
                <div class="row">

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Position :</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('position', null, ['placeholder' => 'e.g: Manager', 'class' => 'form-control', 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Minimum Age :</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('age', null, ['placeholder' => 'e.g: 25', 'class' => 'form-control numeric', 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Salary :</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('salary', null, ['placeholder' => 'e.g: 12000', 'class' => 'form-control numeric', 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Experience (Years):</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('experience', null, ['placeholder' => 'e.g: 2', 'class' => 'form-control', 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Require Two Wheeler License?</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('two_wheeler_status', [10 => 'No', 11 => 'Yes'], null, [
                                        'class' => 'form-control select-search',
                                        'disabled',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Require Four Wheeler License?</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('four_wheeler_status', [10 => 'No', 11 => 'Yes'], null, [
                                        'class' => 'form-control select-search',
                                        'disabled',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Created By</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('created_by', optional($mrfModel->createrUser)->full_name, [
                                        'class' => 'form-control',
                                        'disabled',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-8">Created At</label>
                            <div class="col-lg-4 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    @php
                                        $createdDate =
                                            setting('calendar_type') == 'BS'
                                                ? date_converter()->eng_to_nep_convert(
                                                    date('Y-m-d', strtotime($mrfModel->created_at)),
                                                )
                                                : date('M d, Y', strtotime($mrfModel->created_at));
                                    @endphp

                                    {!! Form::text('created_at', $createdDate, ['class' => 'form-control', 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @if (count($mrfModel->statusDetailModels) > 0)
            <div class="row">
                @foreach ($mrfModel->statusDetailModels as $key => $statusDetailModel)
                    @if (in_array($statusDetailModel->status, [5, 6, 7, 8]))
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    @php
                                        if ($statusDetailModel->status == 5) {
                                            $title = 'Division HR';
                                        } elseif ($statusDetailModel->status == 6) {
                                            $title = 'Business Head';
                                        } elseif ($statusDetailModel->status == 7) {
                                            $title = 'HR Head';
                                        } else {
                                            $title = 'MD';
                                        }
                                    @endphp
                                    <legend class="text-uppercase font-size-sm font-weight-bold">{{ $title }}
                                    </legend>
                                    <ul class="media-list">
                                        <li class="media mt-2">
                                            <span class="font-weight-semibold">Full Name : </span>
                                            <div class="ml-auto">
                                                {{ optional($statusDetailModel->actionByEmployeeModel)->full_name }}
                                            </div>
                                        </li>
                                        <li class="media mt-2">
                                            <span class="font-weight-semibold">Forwarded At : </span>
                                            @php
                                                $actionDate =
                                                    setting('calendar_type') == 'BS'
                                                        ? date_converter()->eng_to_nep_convert(
                                                            date(
                                                                'Y-m-d',
                                                                strtotime($statusDetailModel->action_datetime),
                                                            ),
                                                        )
                                                        : date(
                                                            'M d, Y',
                                                            strtotime($statusDetailModel->action_datetime),
                                                        );
                                            @endphp
                                            <div class="ml-auto">{{ $actionDate }}</div>
                                        </li>
                                        <li class="media mt-2">
                                            <span class="font-weight-semibold">Remark : </span>
                                            <div class="ml-auto">{{ $statusDetailModel->action_remark }}</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    @php
                                        if ($statusDetailModel->status == 4) {
                                            $title = 'Rejected';
                                        } elseif ($statusDetailModel->status == 9) {
                                            $title = 'Cancelled';
                                        } else {
                                            $title = '';
                                        }
                                    @endphp
                                    <legend class="text-uppercase font-size-sm font-weight-bold">Status Detail</legend>
                                    <ul class="media-list">
                                        <li class="media mt-2">
                                            <span class="font-weight-semibold">Status : </span>
                                            <div class="ml-auto">
                                                <span
                                                    class="badge badge-{{ $mrfModel->getStatusWithColor()['color'] }}">
                                                    {{ $mrfModel->getStatusWithColor()['status'] }}
                                                </span>
                                            </div>
                                        </li>
                                        <li class="media mt-2">
                                            <span class="font-weight-semibold">Changed At : </span>
                                            @php
                                                $actionDateTime =
                                                    setting('calendar_type') == 'BS'
                                                        ? date_converter()->eng_to_nep_convert(
                                                            date(
                                                                'Y-m-d',
                                                                strtotime($statusDetailModel->action_datetime),
                                                            ),
                                                        )
                                                        : date(
                                                            'M d, Y',
                                                            strtotime($statusDetailModel->action_datetime),
                                                        );
                                            @endphp
                                            <div class="ml-auto">{{ $actionDateTime }}</div>
                                        </li>
                                        <li class="media mt-2">
                                            <span class="font-weight-semibold">Remark : </span>
                                            <div class="ml-auto">{{ $statusDetailModel->action_remark }}</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
</div>

{!! Form::close() !!}

@endsection
