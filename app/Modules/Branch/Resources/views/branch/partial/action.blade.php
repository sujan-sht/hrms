<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
{{-- <script src="{{asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script> --}}
{{-- <script src="{{asset('admin/global/js/demo_pages/form_multiselect.js')}}"></script> --}}

<div class="card">
    <div class="card-body">
        <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
        <div class="form-group row">
            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Organization :<span class="text-danger"> *</span></label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::select(
                                'organization_id',
                                $organizationList,
                                $value = count($organizationList) === 1 ? array_key_first($organizationList->toArray()) : null,
                                ['placeholder' => 'Select Organization', 'class' => 'form-control select-search']
                            ) !!}
                        </div>
                        @if($errors->has('organization_id'))
                            <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Name :<span class="text-danger"> *</span></label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-office"></i></span>
                            </span>
                            {!! Form::text('name', null, ['placeholder'=>'Enter Name','class'=>'form-control']) !!}
                        </div>
                        @if($errors->has('name'))
                            <div class="error text-danger">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Province :<span class="text-danger"> *</span></label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">

                            {!! Form::select('provinces_districts_id', $province ?? [], null, [
                            'placeholder' => 'Select Province',
                            'class'=>'form-control select-search',
                            'id' => 'provinceSelect'
                        ]) !!}
                        </div>
                        @if($errors->has('provinces_districts_id'))
                        <div class="error text-danger">{{ $errors->first('provinces_districts_id') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">District :<span class="text-danger"> *</span></label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::select('district_id', $districtList ?? [], null, [
                            'placeholder' => 'Select District',
                            'class'=>'form-control select-search',
                             'id' => 'districtSelect',
                        ]) !!}
                        </div>
                        @if($errors->has('district_id'))
                        <div class="error text-danger">{{ $errors->first('district_id') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-3 mb-2">
                <label class="form-label">Provinces</label>
                {!! Form::select('id[]', $province ?? [], $request->input('id', []), [
                    'class'=>'form-control multiselect-filtering',
                    'multiple' => 'multiple'
                ]) !!}
            </div>


            <div class="col-md-3 mb-2">
                <label class="form-label">District List</label>
                {!! Form::select('district_id[]', $districtList ?? [], $request->input('district_id', []), [
                    'class'=>'form-control multiselect-filtering',
                    'multiple' => 'multiple'
                ]) !!}
            </div> --}}

            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Location :</label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-location4"></i></span>
                            </span>
                            {!! Form::text('location', null, ['placeholder'=>'Enter Location','class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Contact :</label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-phone2"></i></span>
                            </span>
                            {!! Form::text('contact', null, ['placeholder'=>'Enter Contact','class'=>'form-control numeric']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Email :</label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-envelop3"></i></span>
                            </span>
                            {!! Form::email('email', null, ['placeholder'=>'Enter Email','class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Manager :</label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::select('manager_id', $employeeList, null, ['placeholder'=>'Select Manager', 'class'=>'form-control select-search']) !!}
                        </div>
                    </div>
                </div>
            
            </div>
            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Branch Code :</label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-code"></i></span>
                            </span>
                            {!! Form::text('branche_code', null, ['placeholder'=>'Enter Branch Code','class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Remote Allowance :</label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            
                            <select name="remote_allowance" class="form-control">
                                <option value="0" {{ $isEdit ? ($branchModel->remote_allowance==0 ? 'selected' : '') : '' }} >Disable</option>
                                <option value="1" {{ $isEdit ? ($branchModel->remote_allowance==1 ? 'selected' : '') : ''}} >Enable</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Day Off:<span class="text-danger">*</span></label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::select(
                                'dayoff',
                                [
                                    'Sunday' => 'Sunday',
                                    'Monday' => 'Monday',
                                    'Tuesday' => 'Tuesday',
                                    'Wednesday' => 'Wednesday',
                                    'Thursday' => 'Thursday',
                                    'Friday' => 'Friday',
                                    'Saturday' => 'Saturday',
                                    'N/A' => 'N/A',
                                ],
                                $branch_day_shift,
                                [
                                    'id' => 'dayoff',
                                    'multiple' => 'multiple',
                                    'name' => 'dayoff[]',
                                    'class' => 'form-control select-day-off',
                                ],
                            ) !!}
                        </div>
                        <span class="text-danger">{{ $errors->first('dayoff') }}</span>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js')}}"></script>
    <script src="{{ asset('admin/validation/branch.js')}}"></script>
   
@endsection
<script type="text/javascript">

    $(document).ready(function() {
        $('.select-day-off').select2({
            placeholder: "Choose Day Off"
        });
    });
</script>