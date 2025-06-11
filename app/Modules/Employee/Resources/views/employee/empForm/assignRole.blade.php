{{-- @if ($user_role)
<div class="form-group row">
    <label class="col-form-label col-lg-3">Role:</label>
    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
         {!! Form::select('role_id',$roles, $value = $user_role, ['id'=>'role_id','class'=>'form-control','placeholder'=>'Enter Role','required'=>'required', $isEmployee ? 'disabled' : '']) !!}
        @if ($isEmployee)
          {!! Form::hidden('role_id', $value = $user_role, ['id'=>'role_id','class'=>'form-control','placeholder'=>'Enter Role','required'=>'required']) !!}
        @endif
    </div>
</div>
@endif
 --}}

@if ($user_role)
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#assigned_role_ids').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true
            });
        })
    </script>
    <div class="form-group row">
        <label class="col-form-label col-lg-3">Role:</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            {!! Form::select('role_id', $roles, $value = $user_role, [
                'id' => 'role_id',
                'class' => 'form-control',
                'placeholder' => 'Enter Role',
                'required' => 'required',
                $isEmployee ? 'disabled' : '',
            ]) !!}
            @if ($isEmployee)
                {!! Form::hidden('role_id', $value = $user_role, [
                    'id' => 'role_id',
                    'class' => 'form-control',
                    'placeholder' => 'Enter Role',
                    'required' => 'required',
                ]) !!}
            @endif
        </div>
    </div>
    @if ($is_edit ?? false)
        <div class="form-group row">
            <div class="col-lg-12">
                <div class="row">
                    <label class="col-form-label col-lg-3">Assigned Roles:</label>
                    <div class="col-lg-9 form-group-feedback">
                        <div class="input-group">
                            {!! Form::select('assigned_role_ids[]', $roles, $assigned_role_ids ?? [], [
                                'class' => 'form-control',
                                'id' => 'assigned_role_ids',
                                'multiple' => 'multiple',
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
