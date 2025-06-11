<div class="row items">
    <div class="col-md-3">
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Request Type:</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend" style="width: 100%">
                        <span class="input-group-text"><i class="icon-color-sampler"></i></span>
                        {!! Form::select('type_id[]', $tadaTypes,  null, ['placeholder'=>'Select Request Type','class'=>'form-control select-search requestType']) !!}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Sub Type:</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend" style="width: 100%">
                        <span class="input-group-text"><i class="icon-color-sampler"></i></span>
                        {!! Form::select('sub_type_id[]', $tadaSubTypes,  null, ['placeholder'=>'Select Sub Type','class'=>'form-control select-search subTypeFilter']) !!}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Quantity:</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {{-- <span class="input-group-prepend">
                        <span class="input-group-text">Rs.</span>
                    </span> --}}
                    {!! Form::text('amount[]', null, ['placeholder'=>'Quantity', 'class'=>'form-control numeric']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group row">
            <label class="col-form-label col-lg-3">Message:</label>
            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('remark[]', null, ['placeholder'=>'Write message here..', 'class'=>'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-1">
        <button id="remove-btn" class="btn btn-danger" onclick="$(this).parents('.items').remove()">
            <i class="icon-minus-circle2"></i>&nbsp;&nbsp;REMOVE
        </button>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.requestType').on('change', function() {
            var type_id = $(this).val()
            var that =  $(this)
            $.ajax({
                url: "<?php echo route('tadaType.getSubTypeList'); ?>",
                method: 'POST',
                data:{
                    _token: "{{ csrf_token() }}",
                    type_id: type_id
                },
                success: function (data) {
                    var list = JSON.parse(data)
                    var options = ''

                    options += "<option value=''>Select Sub Type</option>"
                    $.each(list, function(id, value){
                        options += "<option value='" + id + "'>" + value + "</option>"
                    })

                    var subType = that.closest('.items').find('.subTypeFilter')
                    subType.html(options)
                    subType.select2({
                        placeholder: "Select Sub Type"
                    })
                }
            })
        })
    })
    </script>

