<div class="card">
    <div class="card-body">
        <fieldset class="mb-3">
            <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Organization :<span class="text-danger"> *</span></label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('organization_id', $organizationList, null, [
                                    'class' => 'select-filter organization-filter',
                                    'placeholder' => 'Select Organization',
                                ]) !!}
                            </div>
                            @if ($errors->has('organization_id'))
                                <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">First Approval (Division  HR):<span class="text-danger"> *</span></label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('first_approval_emp_id', $employeeList, null, [
                                    'class' => 'select-filter',
                                    'placeholder' => 'Choose Employee'
                                ]) !!}
                            </div>
                            @if ($errors->has('first_approval_emp_id'))
                                <div class="error text-danger">{{ $errors->first('first_approval_emp_id') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Second Approval (Business Head):</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('second_approval_emp_id', $employeeList, null, [
                                    'class' => 'select-filter',
                                    'placeholder' => 'Choose Employee'
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Third Approval (HR Head):</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('third_approval_emp_id', $employeeList, null, [
                                    'class' => 'select-filter',
                                    'placeholder' => 'Choose Employee'
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">Fourth Approval (MD):</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::select('fourth_approval_emp_id', $employeeList, null, [
                                    'class' => 'select-filter',
                                    'placeholder' => 'Choose Employee'
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/validation/mrf-approval-flow.js') }}"></script>
@stop
