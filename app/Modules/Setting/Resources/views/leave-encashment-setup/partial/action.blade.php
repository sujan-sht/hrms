<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Organization <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::select('organization_id', $organizationList, null, ['class' => 'form-control select-search organization', 'placeholder' => 'Select Organization']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Income Type <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::select('income_type', [], isset($leaveEncashmentSetup) ? $leaveEncashmentSetup->income_type : null, ['class' => 'form-control income-type-filter', 'placeholder' => 'Select Income Type']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Month <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::select('month', $monthList, null, ['class' => 'form-control select-search']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                        class="icon-backward2"></i></b>Go Back</a>
            <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
            class="icon-database-insert"></i></b>{{ $btnType }}</button>
        </div>
    </div>
</div>

@section('script')
    <script src="{{ asset('admin/validation/leave-encashment-setup.js') }}"></script>
    <script>
        $(document).ready(function() {
            var is_edit = '{{ isset($isEdit) ? $isEdit : false }}';
    
            // Setup CSRF token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
    
            $('.organization').on('change', function() {
                var organizationId = $(this).val();
                if (!organizationId) return;
    
                $.ajax({
                    type: 'GET',
                    url: '{{ route("organization.getIncomeTypes") }}',
                    data: { organization_id: organizationId },
                    success: function(data) {
                        try {
                            var list = JSON.parse(data);
                            var options = "<option value=''>Select Income Type</option>";
    
                            // Populate options dynamically
                            $.each(list, function(id, value) {
                                options += "<option value='" + id + "'>" + value + "</option>";
                            });
                            $('.income-type-filter').html(options).select2();
    
                            // Pre-select income type if editing
                            @if(isset($leaveEncashmentSetup) && !empty($leaveEncashmentSetup))
                                var selectedIncomeType = '{{ $leaveEncashmentSetup->income_type }}';
                                if (is_edit == '1' && selectedIncomeType) {
                                    $('.income-type-filter').val(selectedIncomeType).trigger('change.select2');
                                }
                            @endif
                        } catch (e) {
                            console.error('Parsing error:', e);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Failed to fetch income types. Please try again.');
                    }
                });
            });
    
            // Trigger change if a default organization exists
            if ($('.organization').val()) {
                $('.organization').trigger('change');
            }
        });
    </script>
    
@endSection


