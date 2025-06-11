@extends('admin::layout')
@section('title') Set Target @endSection
@section('breadcrum')
    <a class="breadcrumb-item" href="{{route('target.index')}}">Targets</a>
    <a class="breadcrumb-item active">Set Target</a>
@endSection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    {!! Form::open(['route'=>'target.setTargetValue','method'=>'POST','class'=>'form-horizontal','id'=>'setTargetValuesFormSubmit','role'=>'form','files' => false]) !!}

        {!! Form::hidden('target_id', $target->id, []) !!}

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-lg-8 mb-4">
                                <div class="row">
                                    <label class="col-form-label col-lg-4">Employee :<span class="text-danger"> *</span></label>
                                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {!! Form::select('employee_ids[]', $employees, null, ['class' => 'form-control multiselect-select-all', 'multiple']) !!}
                                        </div>
                                        @if ($errors->has('employee_ids'))
                                            <div class="error text-danger">{{ $errors->first('employee_ids') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="row">
                                    <label class="col-form-label col-lg-4">Set Values :</label>
                                    <div class="col-lg-8">
                                        <table class="table table-hover" style="border-collapse: inherit;">
                                            <tr class="btn-slate">
                                                <th>Quarter</th>
                                                <th>Target Value</th>
                                            </tr>
                                            <tbody>
                                                @if(!empty($target))
                                                    @for($i =1; $i <= $target->no_of_quarter; $i++)
                                                        <tr>
                                                            <td style="border-top:0px">{{ 'Q'.$i }}</td>
                                                            <td style="border-top:0px">
                                                                {!! Form::text('target_values['.$i.']', null, ['class'=>'numeric form-control']) !!}
                                                            </td>
                                                        </tr>
                                                    @endfor
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-lg-6 mb-4">
                                <div class="row">
                                    <label class="col-form-label col-lg-4">KRA :</label>
                                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {{ $target->kraInfo->title }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-4">
                                <div class="row">
                                    <label class="col-form-label col-lg-4">KPI :</label>
                                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {{ $target->kpiInfo->title }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-4">
                                <div class="row">
                                    <label class="col-form-label col-lg-4">Target :</label>
                                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                        <div class="input-group">
                                            {{ $target->title }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-database-insert"></i></b>Save Record</button>
        </div>

    {!! Form::close() !!}

    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>

@endsection
