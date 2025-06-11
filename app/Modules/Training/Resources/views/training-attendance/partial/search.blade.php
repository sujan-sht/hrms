<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => ['training-attendance.index', $trainingModel->id], 'method' => 'get']) !!}
        <div class="row">
            <div class="col-md-3">
                <label class="d-block font-weight-semibold">Attendee:</label>
                <div class="input-group">
                    @php
                        if (isset($_GET['employee_id'])) {
                            $employeeValue = $_GET['employee_id'];
                        } else {
                            $employeeValue = null;
                        }
                    @endphp
                    {!! Form::select('employee_id', $employeeList, $value = $employeeValue, [
                        'placeholder' => 'Select Employee',
                        'class' => 'form-control select-search',
                    ]) !!}
                </div>
            </div>
            <div class="col-md-3">
                <label class="d-block font-weight-semibold">Contact Number:</label>
                <div class="input-group">
                    {!! Form::text('contact_no', request('contact_no') ?? null, ['placeholder'=>'Enter Contact number..', 'class'=>'form-control']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <label class="d-block font-weight-semibold">Email:</label>
                <div class="input-group">
                    {!! Form::text('email', request('email') ?? null, ['placeholder'=>'Enter email..', 'class'=>'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
            <a href="{{ route('training-attendance.index', $trainingModel->id) }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js')}}"></script>
@endSection

