<div class="row">
    <div class="col-md-4 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-4">OT:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('ot', $statusList, $value = null, [
                        'id' => 'ot',
                        'placeholder' => 'Select Status',
                        'class' => 'form-control select-search',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row otDetail" style="display:none">
    @foreach ($otType as $key => $value)
        <div class="col-md-4 mb-3">
            <div class="row">
                <label class="col-form-label col-lg-4">{{ $value }}</label>
                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <input type="hidden" name="ot_type[]"
                        value="{{$key}}" class="form-control">
                        {!! Form::text('rate[]', $value =$is_edit ? ($employees->findOtByType($employees->id,$key) ? $employees->findOtByType($employees->id,$key)->rate : null) : null, [
                            'id' => 'rate',
                            'placeholder' => 'Enter Rate',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
    $(document).ready(function() {
        $('#ot').on('change', function() {
            var status = $(this).val();
            console.log(status);
            if (status == '11') {
                $('.otDetail').show();
            } else {
                $('.otDetail').hide();
            }
        });
        $('#ot').trigger('change');
    })
</script>
