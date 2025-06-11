<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Stock Detail</legend>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">Asset : <span class="text-danger">*</span></label>
                                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::select('asset_id', $assets, $value = null, [
                                                    'class' => 'form-control asset select-search',
                                                    'placeholder' => 'Select Asset',
                                                    $isEdit ? 'disabled' : ''
                                                ]) !!}
                                            </div>
                                            <span class="assetError"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">Code : <span class="text-danger">*</span></label>
                                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::text('code', null, [
                                                    'rows' => 5,
                                                    'placeholder' => 'Enter code here..',
                                                    'class' => 'form-control',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">Quantity : <span class="text-danger">*</span></label>
                                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::text('quantity', null, [
                                                    'rows' => 5,
                                                    'placeholder' => 'Enter quantity here..',
                                                    'class' => 'form-control numeric',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">Expiry Date :</label>
                                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                @php
                                                    $expiryDate = null;
                                                    if(setting('calendar_type') == 'BS'){
                                                        $clData = 'form-control nepali-calendar date';
                                                        if($isEdit && $assetQuantityModel['expiry_date']){
                                                            $expiryDate = date_converter()->eng_to_nep_convert($assetQuantityModel['expiry_date']);
                                                        }
                                                    }else{
                                                        $clData = 'form-control daterange-single date';
                                                        if($isEdit && $assetQuantityModel['expiry_date']){
                                                            $expiryDate = $assetQuantityModel['expiry_date'];
                                                        }
                                                    }
                                                @endphp
                                                {!! Form::text('expiry_date', $expiryDate, [
                                                    'rows' => 5,
                                                    'placeholder' => 'Select date..',
                                                    'class' => $clData,
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
        class="icon-backward2"></i></b>Go Back</a>

    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>


@section('script')
    <script src="{{ asset('admin/validation/asset-quantity.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('.asset').on('change', function () {
                let asset_id = $(this).val()
                checkAssetExists(asset_id)
            })

            function checkAssetExists(asset_id) {
                $.ajax({
                    type: 'GET',
                    url: "{{route('assetQuantity.checkAssetExists')}}",
                    dataType: 'json',
                    data: {
                        'asset_id' : asset_id
                    },
                    success: function (resp) {
                        if(resp){
                            $('.asset').css('border-color', 'red')
                            $('.assetError').html(
                                '<i class="icon-thumbs-down3 mr-1"></i>Stock already set for this Asset.'
                            )
                            $('.assetError').removeClass('text-success')
                            $('.assetError').addClass('text-danger')
                            $('.asset').focus()
                            $('.asset').val('')
                        }else{
                            $('.asset').css('border-color', 'green')
                            $('.assetError').html('')
                            $('.assetError').removeClass('text-danger')
                            $('.assetError').addClass('text-success')
                        }
                    }
                })
            }
        })
    </script>
@endSection
