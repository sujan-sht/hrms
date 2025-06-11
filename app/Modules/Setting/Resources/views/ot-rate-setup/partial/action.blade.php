<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="example-email" class="form-label">Is there any minimum OT requirement?</label>
                    {!! Form::select('is_min_ot_requirement', [10=> 'No', 11=> 'Yes'], $otRateSetupModel->first()->is_min_ot_requirement ?? null, [
                        'placeholder' => 'Select Option',
                        'class' => 'form-control isOT select2',
                    ]) !!}
                </div>
            </div>

            <div class="col-md-3 minOtTime" style="display: none;">
                <div class="mb-3">
                    <label for="example-email" class="form-label">Minimum OT Time (in mins) <span class="text-danger">*</span></label>
                    {!! Form::text('min_ot_time', $otRateSetupModel->first()->min_ot_time ?? null, ['class' => 'form-control numeric', 'placeholder' => 'Enter number...', 'required']) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="text-light btn-slate">
                <th width="10%">#</th>
                <th width="20%">OT Type</th>
                <th width="20%" class="income_heading">Income Heading</th>
                <th width="15%" class="times_value">Times Calculation value</th>
                <th width="15%" class="rate" style="display:none">Rate</th>
            </tr>
        </thead>
        <tbody>
            @if($is_edit)
            {{-- @dd($otRateSetupModel->first()->ot_basis); --}}
                <div class="col-lg-6 form-group-feedback form-group-feedback-right mb-3">
                    <div class="input-group">
                        <div class="p-1 rounded">
                            <div class="custom-control custom-radio custom-control-inline">
                                {{ Form::radio('ot_basis', 1, $otRateSetupModel->first()->ot_basis == 1 ? true : false, ['class' => 'custom-control-input otBasis', 'id' => 'radio1']) }}
                                <label class="custom-control-label mr-3" for="radio1">Salary Basis</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                {{ Form::radio('ot_basis', 2, $otRateSetupModel->first()->ot_basis == 2 ? true : false, ['class' => 'custom-control-input otBasis', 'id' => 'radio2']) }}
                                <label class="custom-control-label mr-3" for="radio2">Fixed Basis</label>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach($otRateSetupModel as $key=>$otModel)
                <tr>
                    <input type="hidden" name="ot_type[]"
                        value="{{$otModel->ot_type}}" class="form-control">
                    <input type="hidden" name="organization_id"
                        value="{{$otModel->organization_id}}" class="form-control">
                    <td>{{$loop->iteration}}</td>
                    <td>{{$otModel->getOtType()}}</td>
                    @php
                    $incomeHeadingIds = [];
                        foreach($otModel->incomeHeadingDetail as $incId => $income){
                            $incomeHeadingIds[] = $income->income_setup_id;
                        }
                       
                    @endphp
                    <td class="income_heading">
                        {!! Form::select('income_setup_id[' . $key . '][]',$incomeList, $value= $incomeHeadingIds, ['id'=>'income_setup_id', 'class'=>'form-control multiselect-filtering', 'multiple' => 'multiple']) !!}
                    </td>
                    <td class="times_value">
                        {!! Form::text('times_value[]',$value = $otModel->times_value, ['id'=>'rate', 'class'=>'form-control numeric']) !!}
                    </td>
                    <td class="rate" style="display:none;">
                        {!! Form::text('rate[]',$value = $otModel->rate, ['id'=>'rate', 'class'=>'form-control numeric']) !!}
                    </td>
                </tr>
                @endforeach
            @else
            <div class="col-lg-6 form-group-feedback form-group-feedback-right mb-3">
                <div class="input-group">
                    <div class="p-1 rounded">
                        <div class="custom-control custom-radio custom-control-inline">
                            {{ Form::radio('ot_basis', 1, true, ['class' => 'custom-control-input otBasis', 'id' => 'radio1']) }}
                            <label class="custom-control-label mr-3" for="radio1">Salary Basis</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            {{ Form::radio('ot_basis', 2, false, ['class' => 'custom-control-input otBasis', 'id' => 'radio2']) }}
                            <label class="custom-control-label mr-3" for="radio2">Fixed Basis</label>
                        </div>
                    </div>
                </div>
            </div>
                @foreach($otType as $key=>$value)
                <tr>
                    <input type="hidden" name="ot_type[]"
                        value="{{$key}}" class="form-control">
                    <input type="hidden" name="organization_id"
                        value="{{$_GET['organization_id']}}" class="form-control">
                    <td>{{$loop->iteration}}</td>
                    <td>{{$value}}</td>
                    <td class="income_heading">
                        {!! Form::select('income_setup_id[' . $key . '][]',$incomeList, $value = null, ['id'=>'income_setup_id', 'class'=>'form-control multiselect-filtering', 'multiple' => 'multiple']) !!}
                    <td class="times_value">
                    {!! Form::text('times_value[]',$value = null, ['id'=>'times_value', 'class'=>'form-control numeric']) !!}
                    </td>
                    <td class="rate" style="display:none;">
                        {!! Form::text('rate[]',$value = null, ['id'=>'rate', 'class'=>'form-control numeric']) !!}
                    </td>
                </tr>
                @endforeach
            @endif

            
        </tbody>
    </table>
    
            <div class="d-flex justify-content-end pt-1 pb-3 pl-3 pr-3">
                <button class="btn bg-teal float-right" type="submit">{{ $btnType }} Changes</button>
            </div>

</div>
<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_multiselect.js')}}"></script>
<script>
    $(document).ready(function() {
        var selectedValue = $("input.otBasis:checked").val();
        if (selectedValue == '1') {
            $('.income_heading').show();
            $('.times_value').show();
            $('.rate').hide();
        }
        else{
            $('.income_heading').hide();
            $('.times_value').hide();
            $('.rate').show();
        }
        // initiate select2
        $('.select2').select2();
        $(".otBasis").on('change', function() {
            var type = $(this).val();
            if (type == '1') {
                $('.income_heading').show();
                $('.times_value').show();
                $('.rate').hide();
            }
            else{
                $('.income_heading').hide();
                $('.times_value').hide();
                $('.rate').show();
            }
        });

        // Check Min OT Requirement
        $('.isOT').on('change', function () {
            var isOT = $(this).val()
            if(isOT == 11){
                $('.minOtTime').css('display', 'block')
            }else{
                $('.minOtTime').css('display', 'none')
            }
        })

        $('.isOT').trigger('change')
    });
   
</script>