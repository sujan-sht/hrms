<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>

    <div class="card-body">
        {!! Form::open([
            'route' => 'labour.viewLabourMonthly',
            'method' => 'GET',
            'class' => 'form-horizontal',
            'role' => 'form',
        ]) !!}
        <div class="row">
            {!! Form::hidden('calendar_type', 'nep', []) !!}
            @if (Auth::user()->user_type == 'super_admin' || Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'hr')
                
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Organization:</label>
                        <div class="input-group">
                            @php $selected_org_id = isset(request()->org_id) ? request()->org_id : null ; @endphp
                            {!! Form::select('org_id', $organizationList, $selected_org_id, [
                                'class' => 'form-control select2 organization-labour-filter',
                                'placeholder' => 'Select Organization',
                            ]) !!}
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Select Labour:</label>
                        <div class="input-group">
                            @php $selected_emp_id = isset(request()->emp_id) ? request()->emp_id : null ; @endphp
                            {!! Form::select('emp_id', $employees, $selected_emp_id, [
                                'class' => 'form-control select2 labour-filter',
                                'placeholder' => 'Select Labour',
                            ]) !!}
                        </div>
                    </div>
                </div>
            @endif

            

        
        <div class="col-md-3 nepdata" >
            <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                <label class="d-block font-weight-semibold">Select Nepali Year: <span class="text-danger">*</span></label>
                <div class="input-group">
                    @php $nep_year = isset(request()->nep_year) ? request()->nep_year : null ; @endphp
                    {!! Form::select('nep_year', $nep_years, $nep_year, [
                        'class' => 'form-control select2 nep_year',
                        'placeholder' => 'Select Nepali Year',
                    ]) !!}
                </div>
            </div>
        </div>

        @if (auth()->user()->user_type == 'super_admin' ||
                auth()->user()->user_type == 'admin' ||
                auth()->user()->user_type == 'hr')
            <div class="col-md-3 mt-2 nepdata">
        @else
            <div class="col-md-3 nepdata">
        @endif
            <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                <label class="d-block font-weight-semibold">Select Nepali Month: <span class="text-danger">*</span></label>
                <div class="input-group">
                    @php $nep_month = isset(request()->nep_month) ? request()->nep_month : null ; @endphp
                    {!! Form::select('nep_month', $nep_months, $nep_month, [
                        'class' => 'form-control select2 nep_month',
                        'placeholder' => 'Select Nepali Month',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-2">
        <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
        {{-- <a href="{{ request()->url() }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a> --}}
    </div>
    {!! Form::close() !!}
    </div>
</div>


<script>
    $(document).ready(function() {

        $('.organization-labour-filter').on('change', function () {
            filterLabourByOrganization();
        });

        function filterLabourByOrganization()
        {
            var organizationId = $('.organization-labour-filter').val();
            $.ajax({
                type: 'GET',
                url: '/admin/organization/get-labour',
                data: {
                    organization_id : organizationId
                },
                success: function(data) {
                    console.log(data);
                    var list = JSON.parse(data);
                    var options = '';

                    options += "<option value=''>Select Labour</option>";
                    $.each(list, function(id, value){
                        options += "<option value='" + id + "'>" + value + "</option>";
                    });

                    $('.labour-filter').html(options);
                    $('.labour-filter').select2({
                        placeholder: "Select Labour"
                    });
                }
            });
        }
        
        let type = $('.calendartype').find(":selected").val();

        if (type == 'eng') {
            $('.engdata').css('display', 'block')
            $('.nepdata').css('display', 'none')

            $('.nep_year').removeAttr('required')
            $('.nep_month').removeAttr('required')

            $('.eng_year').attr('required', true)
            $('.eng_month').attr('required', true)
        }
        if (type == 'nep') {
            $('.engdata').css('display', 'none')
            $('.nepdata').css('display', 'block')

            $('.nep_year').attr('required', true)
            $('.nep_month').attr('required', true)

            $('.eng_year').removeAttr('required')
            $('.eng_month').removeAttr('required')
        }

        $('.select2').select2();
    })



    $(document).on('change', '.calendartype', function() {
        let type = $(this).val();

        if (type == 'eng') {
            $('.engdata').css('display', 'block')
            $('.nepdata').css('display', 'none')

            $('.nep_year').removeAttr('required')
            $('.nep_month').removeAttr('required')

            $('.eng_year').attr('required', true)
            $('.eng_month').attr('required', true)

            $('.nep_year').val('')
            $('.nep_month').val('')
        }
        if (type == 'nep') {
            $('.engdata').css('display', 'none')
            $('.nepdata').css('display', 'block')

            $('.nep_year').attr('required', true)
            $('.nep_month').attr('required', true)

            $('.eng_year').removeAttr('required')
            $('.eng_month').removeAttr('required')

            $('.eng_year').val('')
            $('.eng_month').val('')
        }
    })
</script>
