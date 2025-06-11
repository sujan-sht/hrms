{!! Form::open([
    'method' => 'POST',
    'id' => 'setting-store-email-setup-submit',
    'class' => 'form-horizontal',
    'role' => 'form',
]) !!}

@inject('emailSetupModel', '\App\Modules\Setting\Entities\EmailSetup')

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="text-light btn-slate">
                <th>S.N</th>
                <th>Module</th>
                <th>Enable Email ?</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($moduleList as $moduleId => $moduleName)
                @php
                    $emailSetup = $emailSetupModel->where('module_id', $moduleId)->first();
                @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>
                        {{ $moduleName }}
                    </td>
                    {!! Form::hidden("setups[$moduleId][module_id]", $moduleId, []) !!}
                    <td>
                        {!! Form::select("setups[$moduleId][status]", $statusList, isset($emailSetup) ? $emailSetup->status : null, [
                            'class' => 'form-control select-search',
                            'placeholder' => 'Select Option',
                        ]) !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="text-right">
    <button type="submit" class="ml-2 mt-2 btn text-white bg-pink btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b> Save</button>
</div>


{!! Form::close() !!}

<script>
    $(document).ready(function() {
        setupCSRFToken();
        //============ setup email submit ===============================
        $('#setting-store-email-setup-submit').on('submit', function(e) {
            e.preventDefault();
            ajaxSubmit("#setting-store-email-setup-submit",
                "{{ route('setting.storeEmailSetupAjax') }}");
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
</script>
