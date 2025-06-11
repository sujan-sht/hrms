<div class="row">
    <div class="col-md-10">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Asset Allocation Detail</legend>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Employee : <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::select('employee_id', $employees, $value = null, [
                                                    'class' => 'form-control select-search',
                                                    'placeholder' => 'Select Employee',
                                                    $isEdit ? 'disabled' : '',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Asset : <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::select('asset_id', $assets, $value = null, [
                                                    'class' => 'form-control select-search chooseAsset',
                                                    'placeholder' => 'Select Asset',
                                                    $isEdit ? 'disabled' : '',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Quantity : <span
                                                class="text-danger">*</span></label>
                                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::text('quantity', null, [
                                                    'rows' => 5,
                                                    'placeholder' => 'Enter quantity here..',
                                                    'class' => 'form-control numeric quantity',$isEdit ? 'disabled' : '',
                                                ]) !!}
                                            </div>
                                            <span class="quantityError"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Allocate Date :</label>
                                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                @php
                                                    $allocatedDate = null;
                                                    if (setting('calendar_type') == 'BS') {
                                                        $clData = 'form-control nepali-calendar date';
                                                        if ($isEdit && $assetAllocateModel['allocated_date']) {
                                                            $allocatedDate = date_converter()->eng_to_nep_convert($assetAllocateModel['allocated_date']);
                                                        }
                                                    } else {
                                                        $clData = 'form-control daterange-single date';
                                                        if ($isEdit && $assetAllocateModel['allocated_date']) {
                                                            $allocatedDate = $assetAllocateModel['allocated_date'];
                                                        }
                                                    }
                                                @endphp
                                                {!! Form::text('allocated_date', $allocatedDate, [
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

                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Return Date :</label>
                                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                @php
                                                    $returnDate = null;
                                                    if (setting('calendar_type') == 'BS') {
                                                        $classData = 'form-control nepali-calendar date';
                                                        if ($isEdit && $assetAllocateModel['return_date']) {
                                                            $returnDate = date_converter()->eng_to_nep_convert($assetAllocateModel['return_date']);
                                                        }
                                                    } else {
                                                        $classData = 'form-control daterange-single date';
                                                        if ($isEdit && $assetAllocateModel['return_date']) {
                                                            $returnDate = $assetAllocateModel['return_date'];
                                                        }
                                                    }
                                                @endphp
                                                {!! Form::text('return_date', $returnDate, [
                                                    'rows' => 5,
                                                    'placeholder' => 'Select date..',
                                                    'class' => $classData,
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type ="hidden" value= "{{$isEdit ? 1 : 0}}" id="isEdit">

                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-lg-12 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Attachment :</label>
                                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {{-- <input type="file" name="attachments[]" class="form-control h-auto"
                                                    accept=".jpg, .png, .doc, .pdf" multiple> --}}
                                                <input type="file" name="attachments[]" class="form-control h-auto"
                                                accept=".jpg, .png, .doc, .pdf">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($isEdit)
                                <div class="col-lg-6">
                                    <div class="col-lg-12 mb-3">
                                        <div class="row">
                                            <ul>
                                                @foreach ($assetAllocateModel->assetAllocateAttachment as $attachment)
                                                    <li>
                                                        <a href="{{ $attachment->attachment }}"
                                                        target="_blank">{{ $attachment->title }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-2">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Remaining Quantity Detail</legend>
                <div class="form-group">
                    <div class="col-lg-12">
                        <div class="row">
                            <label class="col-form-label col-lg-6">Quantity : </label>
                            <span class=" col-lg-6 mb-5 showRemainingQuantity"></span>
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
    <script src="{{ asset('admin/validation/asset-allocate.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.chooseAsset').on('change', function() {
                var isEdit = $('#isEdit').val();
                if(isEdit == 0){
                    $('.quantity').val('')
                }

                let asset_id = $(this).val()
                $.ajax({
                    type: 'GET',
                    url: "{{ route('assetQuantity.checkAssetExists') }}",
                    dataType: 'json',
                    data: {
                        'asset_id': asset_id
                    },
                    success: function(resp) {
                        if (resp) {
                            $('.showRemainingQuantity').html(resp.remaining_quantity)

                            $('.asset').css('border-color', 'red')
                            $('.quantity').prop("max", resp.remaining_quantity)

                            // $('.quantityError').html('Remaining Quantity is ' + resp.remaining_quantity)
                            $('.quantityError').removeClass('text-success')
                            $('.quantityError').addClass('text-danger')
                            $('.quantity').focus()
                        } else {
                            $('.quantity').css('border-color', 'green')
                            $('.quantity').prop("max", 'disabled')

                            // $('.quantityError').html('')
                            $('.quantityError').removeClass('text-danger')
                            $('.quantityError').addClass('text-success')
                        }
                    }
                })
            })

            //check max remaining quantity for edit form
            let is_edit = '{{ $isEdit }}'
            if (is_edit) {
                $('.chooseAsset').trigger('change')
            }
            //
        })
    </script>
@endSection
