@extends('admin::layout')
@section('title')
    Training Participant
@endSection
@section('breadcrum')
    <a href="{{ route('training-participant.index', $training_id) }}" class="breadcrumb-item">Training Participant</a>
    <a class="breadcrumb-item active">Edit</a>
@endSection

@section('content')
    <div class="card">
        <div class="card-body">

            {!! Form::model($trainingParticipantModel, [
                'method' => 'PUT',
                'route' => ['training-participant.update', ['training_id' => $training_id, 'id' => $trainingParticipantModel->id]],
                'class' => 'form-horizontal',
                'id' => 'trainingParticipantFormSubmit',
                'role' => 'form',
                'files' => true,
            ]) !!}

            {{-- @include('training::training-participant.partial.action',['btnType'=>'Update Record']) --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <legend class="text-uppercase font-size-sm font-weight-bold">Training Participant Details
                            </legend>
                            <div class="form-group row">
                                <div class="col-lg-4 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-4">Participant Name :<span class="text-danger">
                                                *</span></label>
                                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::select('employee_id', $employeeList, $value ?? null, [
                                                    'rows' => 5,
                                                    'placeholder' => 'Write Participant Name..',
                                                    'class' => 'form-control',
                                                    'readonly'=>true,
                                                ]) !!}
                                            </div>
                                            @if ($errors->has('employee_id'))
                                                <div class="error text-danger">{{ $errors->first('employee_id') }}</div>
                                            @endif
                                        </div>

                                        {{-- <label class="col-form-label col-lg-2">Employee :<span class="text-danger"> *</span></label>
                                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                            {!! Form::select('employees[]', $employeeList, $value = null, [
                                                'class' => 'form-control multiselect-select-all',
                                                'multiple' => 'multiple',
                                                'id' => 'employees',
                                            ]) !!}
                                            @if ($errors->has('employees'))
                                                <div class="error text-danger">{{ $errors->first('employees') }}</div>
                                            @endif
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-4">Contact Number :<span class="text-danger">
                                                *</span></label>
                                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::text('contact_no', $value ?? null, [
                                                    'rows' => 5,
                                                    'placeholder' => 'Enter Contact Number..',
                                                    'class' => 'form-control numeric',
                                                ]) !!}
                                            </div>
                                            @if ($errors->has('contact_no'))
                                                <div class="error text-danger">{{ $errors->first('contact_no') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-4">Email :<span class="text-danger">
                                                *</span></label>
                                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::text('email', $value ?? null, [
                                                    'rows' => 5,
                                                    'placeholder' => 'Write Email Here..',
                                                    'class' => 'form-control',
                                                ]) !!}
                                            </div>
                                            @if ($errors->has('email'))
                                                <div class="error text-danger">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-8 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Remarks :<span class="text-danger"> *</span></label>
                                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::text('remarks', null, [
                                                    'rows' => 5,
                                                    'placeholder' => 'Write Remarks Here..',
                                                    'class' => 'form-control',
                                                ]) !!}
                                            </div>
                                            @if ($errors->has('remarks'))
                                                <div class="error text-danger">{{ $errors->first('remarks') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i
                            class="icon-database-insert"></i></b>Update Record</button>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endSection
