<div class="table-responsive">

    <table class="table table-hover">
        <thead>
            <tr class="text-light btn-slate">
                @foreach($deduction as $key=>$value)
                <th>{{$value->title}}</th>
                @endforeach

            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($deduction as $key=>$value)
                @if($value->thresholdBenefitSetup != null)
                    <td><input type="number" name="{{$value->thresholdBenefitSetup->deduction_setup_id}}"  value ="{{$value->thresholdBenefitSetup->amount}}" class="form-control" placeholder="Enter income"></td>
                @else

                    <td><input type="number" name="{{$value->id}}"  value ="" class="form-control" placeholder="Enter income"></td>
                @endif
                @endforeach
            </tr>

           
        </tbody>
    </table>



</div>
<div class="text-right">
    <button type="submit" class="ml-2 mt-1 btn text-white bg-pink btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b> Save</button>
</div>
