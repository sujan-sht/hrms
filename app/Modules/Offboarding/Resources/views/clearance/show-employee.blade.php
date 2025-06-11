@extends('admin::layout')
@section('title') Clearances @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Clearances</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
<form action="{{ route('clearance.employee.store', $clearanceModel->id) }}" method="POST">
    @csrf
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Clearance Employees of {{ $clearanceModel->title }}
                </h6>
                <span>{{ $clearanceModel->description }}<span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="text-light btn-slate">
                                    <th>S.N</th>
                                    <th>Organization</th>
                                    <th>Employee</th>
                                    <th width="12%" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($clearanceModel->clearanceResponsible as $key => $value)
                                    {!! Form::hidden('offboard_clearance_id[' . $value->employee_id . ']', $clearanceModel->id, [
                                        'class' => 'form-control',
                                    ]) !!}
                                    {!! Form::hidden('offboard_clearance_responsible_id[' . $value->employee_id . ']', $value->id, [
                                        'class' => 'form-control',
                                    ]) !!}
                                    {!! Form::hidden('employee_id[' . $value->employee_id . ']', $value->employee_id, ['class' => 'form-control']) !!}
                                    {!! Form::hidden('offboard_resignation_id[' . $value->employee_id . ']', $resignationModel->id, [
                                        'class' => 'form-control',
                                    ]) !!}
                                    <tr>
                                        <td width="5%">#{{ ++$key }}</td>
                                        <td>{{ optional($value->organization)->name }}</td>
                                        <td>{{ $value->employee->getFullName() }}</td>
                                        {{-- {{dd($user_id)}} --}}
                                        @if ($user_id == $value->employee->getUser->id)
                                            @if (isset($employeeClearance))
                                            {{-- {{dd($employeeClearance->status)}} --}}
                                                {!! Form::select(
                                                    'status[' . $value->employee_id . ']',
                                                    $employeeClearance->status,
                                                    [10 => 'Unverfied', 11 => 'Verified'],
                                                    null,
                                                    [
                                                        'class' => 'form-control select-search',
                                                    ],
                                                ) !!}

                                                @if ($errors->has('status[]'))
                                                    <div class="error text-danger">{{ $errors->first('status') }}
                                                    </div>
                                                @endif
                                            @else
                                                <td>
                                                    {!! Form::select('status[' . $value->employee_id . ']', [10 => 'Unverfied', 11 => 'Verified'], null, [
                                                        'class' => 'form-control select-search',
                                                    ]) !!}

                                                    @if ($errors->has('status[]'))
                                                        <div class="error text-danger">{{ $errors->first('status') }}
                                                        </div>
                                                    @endif

                                                </td>
                                            @endif
                                        @else
                                            <td>
                                                {!! Form::select('status[' . $value->employee_id . ']', [10 => 'Unverfied', 11 => 'Verified'], null, [
                                                    'class' => 'form-control select-search ',
                                                    'disabled',
                                                ]) !!}

                                                @if ($errors->has('status'))
                                                    <div class="error text-danger">{{ $errors->first('status') }}</div>
                                                @endif

                                            </td>
                                        @endif
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ url()->previous() }}"
                            class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                                    class="icon-backward2"></i></b>Go Back</a>
                        <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                                    class="icon-database-insert"></i></b>Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Resignation Employee Detail</legend>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Employee Name:</label>
                                <div class="col-lg-7 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {{ optional($resignationModel->employeeModel)->getFullName() }}
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>



</form>

@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
@endSection
