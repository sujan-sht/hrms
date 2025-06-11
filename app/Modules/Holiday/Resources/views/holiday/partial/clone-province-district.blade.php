
<div class="form-group clone-div row" style="margin-top: -20px;">
    <div class="row cloned-fields col-lg-10">
        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-4">Provinces :<span class="text-danger"> *</span></label>
                <div class="col-lg-8 form-group-feedback">
                    <div class="input-group">
                        {!! Form::select('province_id[]', $provinceList ?? [], null, [
                            'placeholder' => 'Select Provinces',
                            'class'=>'form-control select-search provinceSelect',
                            'required'
                        ]) !!}

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <label class="col-form-label col-lg-4">District :<span class="text-danger"> *</span></label>
                <div class="col-lg-8 form-group-feedback">
                    <div class="input-group">
                        {!! Form::select('district_id[]', $districtList ?? [], null, [
                            'placeholder' => 'Select District',
                            'class'=>'form-control multiselect-filtering districtSelect',
                            'multiple' => 'multiple',
                            'required'
                        ]) !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <label for="" class="btn btn-danger rounded-pill minus"><i class="icon-minus-circle2 mr-1"></i> Remove</label>
    </div>
</div>



<script>

$('.districtSelect').multiselect({
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true
});
$('.select-search').select2();

</script>
