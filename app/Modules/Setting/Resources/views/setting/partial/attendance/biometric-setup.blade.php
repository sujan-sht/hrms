{!! Form::open([
    'method' => 'POST',
    'class' => 'form-horizontal',
    'role' => 'form',
    'id' => 'biometric-setup-ajax',
]) !!}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <fieldset class="mb-3">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Organization:<span class="text-danger">*</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-office"></i></span>
                                </span>
                                {!! Form::select('organization_id', $organizations, null, [
                                    'id' => 'organization_id',
                                    'class' => 'form-control',
                                    'placeholder' => 'Choose Organization',
                                ]) !!}
                            </div>
                            @if ($errors->has('organization_id'))
                                <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">IP Address:<span class="text-danger">*</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                                </span>
                                {!! Form::text('ip_address', null, [
                                    'id' => 'ip_address',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter IP Address',
                                ]) !!}
                            </div>
                            @if ($errors->has('ip_address'))
                                <div class="error text-danger">{{ $errors->first('ip_address') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Port:<span class="text-danger">*</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                                </span>
                                {!! Form::number('port', null, [
                                    'id' => 'port',
                                    'class' => 'form-control',
                                    'numeric' => true,
                                    'placeholder' => 'Enter Port',
                                ]) !!}
                            </div>
                            @if ($errors->has('port'))
                                <div class="error text-danger">{{ $errors->first('port') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Device Id:<span class="text-danger">*</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                                </span>
                                {!! Form::text('device_id', null, [
                                    'id' => 'device_id',
                                    'class' => 'form-control',
                                    'numeric' => true,
                                    'placeholder' => 'Enter Device Id',
                                ]) !!}
                            </div>
                            @if ($errors->has('device_id'))
                                <div class="error text-danger">{{ $errors->first('device_id') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Communication Password:<span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-key"></i></span>
                                </span>
                                {!! Form::text('communication_password', null, [
                                    'id' => 'communication_password',
                                    'class' => 'form-control',
                                    'numeric' => true,
                                    'placeholder' => 'Enter Password',
                                ]) !!}
                            </div>
                            @if ($errors->has('communication_password'))
                                <div class="error text-danger">{{ $errors->first('communication_password') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Location:</label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-database-check"></i></span>
                                </span>
                                {!! Form::text('location', null, [
                                    'id' => 'location',
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Location',
                                ]) !!}
                            </div>
                            @if ($errors->has('location'))
                                <div class="error text-danger">{{ $errors->first('location') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Status:<span class="text-danger">*</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text"><i class="icon-database-check"></i></span>
                                </span>
                                {!! Form::select('status', ['1' => 'Active', '0' => 'In-Active'], null, [
                                    'id' => 'status',
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                            @if ($errors->has('status'))
                                <div class="error text-danger">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                class="icon-database-insert"></i></b>Save</button>
</div>
{!! Form::close() !!}


<script>
    console.log('====== Biometric Setup AJAX ======');
    $(document).ready(function() {

        setupCSRFToken();
        //============ setup email submit ===============================
        $('#biometric-setup-ajax').on('submit', function(e) {
            e.preventDefault();

            ajaxSubmit("#biometric-setup-ajax",
                "{{ route('deviceManagement.storeAjax') }}");
        });
        //============ end setup email submit ============================


        // ajax submit
        function ajaxSubmit(formId, routeURL) {
            var formData = new FormData();
            $.each($(formId).serializeArray(), function(key, value) {
                formData.append(value.name, value.value);
            });
            $.ajax({
                url: routeURL,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status) {
                        $('.icon-spinner').replaceWith('');
                        toastr.success(response.message, 'Success');
                    } else {
                        toastr.error(response.message, 'Error');
                    }
                    removeSpinnerLoading(formId);
                },
                error: function(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    toastr.error(response.message, 'Error');
                    removeSpinnerLoading(formId);
                },
                complete: function() {
                    $(formId)[0].reset();
                    removeSpinnerLoading(formId);
                }
            });
        }


        // setup CSRF token
        function setupCSRFToken() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

        // remove spinner
        function removeSpinnerLoading(that) {
            $(that).find('button[type=submit]')
                .attr('disabled', false).find('.spinner').remove();
        }

    });
    console.log('====== End Biometric Setup AJAX ======');
</script>
