<div class="form-group row">
    {!! Form::hidden('id', $document->id, []) !!}
    <div class="col-md-12">
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Type :<span class="text-danger"> *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('type_id', $typeList, $value = null, [
                        'placeholder' => 'Select Type',
                        'class' => 'form-control select-search type',
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="form-group row extendExpiryDate d-none">
            <label class="col-form-label col-lg-3">Extend Expiry Date :<span class="text-danger"> *</span></label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-calendar22"></i></span>
                    </span>
                    {!! Form::text('visa_expiry_date', $value = null, [
                        'placeholder' => 'Enter expiry date',
                        'class' => 'form-control daterange-single',
                    ]) !!}
                </div>
            </div>
        </div>

        <div class="deleteDocument d-none">
            <div class="form-group row">
                <label class="col-form-label col-lg-3">Delete Document:</label>
                <div class="col-lg-9">
                    <a class="btn btn-outline-danger btn-icon confirmDelete" link="{{ route('employee.destroyDocumentDetail', $document->id) }}" data-popup="tooltip" data-placement="top" data-original-title="Delete">
                        <i class="icon-trash-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('.type').on('change', function () {
            var type = $(this).val()
            if (type == 1) {
                $('.extendExpiryDate').removeClass('d-none')
                $('.deleteDocument').addClass('d-none')
                $('.saveButton').show()
            }else{
                $('.extendExpiryDate').addClass('d-none')
                $('.deleteDocument').removeClass('d-none')
                $('.saveButton').hide()
            }
        })
    })
</script>