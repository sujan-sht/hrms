<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">For Unmarried</legend>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>Annual Income</th>
                                <th>Tax Rate</th>
                                <th>Tax Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($taxSlabList) > 0)
                                @foreach ($taxSlabList->where('type', 'unmarried') as $i => $item)
                                    <tr>
                                        <td>{!! Form::text('unmarried[' . $item->order . '][annual_income]', $item->annual_income, [
                                            'class' => 'form-control check-annual-income','placeholder'=>'Annual Income'
                                        ]) !!}</td>
                                        <td>{!! Form::number('unmarried[' . $item->order . '][tax_rate]', $item->tax_rate, ['class' => 'form-control','placeholder'=>'Tax Rate']) !!}</td>
                                        <td>{!! Form::number('unmarried[' . $item->order . '][tax_amount]', $item->tax_amount, ['class' => 'form-control','placeholder'=>'Tax Amt']) !!}</td>
                                    </tr>
                                @endforeach
                            @else
                                @for ($i = 0; $i < 6; $i++)
                                    <tr>
                                        <td>{!! Form::text('unmarried[' . $i . '][annual_income]', null, ['class' => 'form-control check-annual-income','placeholder'=>'Annual Income']) !!}</td>
                                        <td>{!! Form::number('unmarried[' . $i . '][tax_rate]', null, ['class' => 'form-control','placeholder'=>'Tax Rate']) !!}</td>
                                        <td>{!! Form::number('unmarried[' . $i . '][tax_amount]', null, ['class' => 'form-control','placeholder'=>'Tax Amt']) !!}</td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">For Married</legend>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>Annual Income</th>
                                <th>Tax Rate</th>
                                <th>Tax Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($taxSlabList) > 0)
                                @php
                                    $j =0;
                                @endphp
                                @foreach ($taxSlabList->where('type', 'married') as $i => $item)
                                    <tr>
                                        <td>{!! Form::text('married[' . $item->order . '][annual_income]', $item->annual_income, ['class' => 'form-control check-annual-income', 'placeholder'=>'Annual Income']) !!}</td>
                                        <td>{!! Form::number('married[' . $item->order . '][tax_rate]', $item->tax_rate, ['class' => 'form-control', 'placeholder'=>'Tax Rate']) !!}</td>
                                        <td>{!! Form::number('married[' . $item->order . '][tax_amount]', $item->tax_amount, ['class' => 'form-control' , 'placeholder'=>'Tax Amount']) !!}</td>
                                    </tr>
                                @endforeach
                            @else
                                @for ($i = 0; $i < 6; $i++)
                                    <tr>
                                        <td>{!! Form::text('married[' . $i . '][annual_income]', null, ['class' => 'form-control check-annual-income','placeholder'=>'Annual Income']) !!}</td>
                                        <td>{!! Form::number('married[' . $i . '][tax_rate]', null, ['class' => 'form-control','placeholder'=>'Tax Rate']) !!}</td>
                                        <td>{!! Form::number('married[' . $i . '][tax_amount]', null, ['class' => 'form-control','placeholder'=>'Tax Amt']) !!}</td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $(document).ready(function() {

            $(".multiDate").flatpickr({
                mode: "multiple",
                dateFormat: "Y-m-d"
            });

            $('.check-annual-income').keyup(function() {
                if (this.value.match(/[^0-9-.]/g)) {
                    this.value = this.value.replace(/[^0-9-.]/g, '');
                }
            });

        });
    </script>
@endSection
