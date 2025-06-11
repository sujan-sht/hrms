<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_select2.js')}}"></script>

<fieldset class="mb-3">
    <legend class="text-uppercase font-size-sm font-weight-bold"></legend>

    <div class="form-group row">
        <label class="col-form-label col-lg-3">Dropdown Field :</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
            {!! Form::select('fid',$field, $value = null, ['id'=>'fid','placeholder'=>'Select Dropdown Field','class'=>'form-control  select-search','data-fouc','required']) !!}
        </div>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-form-label col-lg-3">Dropdown Value:<span class="text-danger">*</span></label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-pen-plus"></i></span>
                        </span>
            {!! Form::text('dropvalue', $value = null, ['id'=>'dropvalue','placeholder'=>'Enter Dropdown dropvalue','class'=>'form-control','required']) !!}
        </div>
        </div>
    </div>
</fieldset>

<div class="text-right">
    <button type="submit" class="ml-2 btn bg-success btn-labeled btn-labeled-left"><b><i class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>
