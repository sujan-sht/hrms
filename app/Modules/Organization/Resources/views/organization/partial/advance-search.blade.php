<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open([
            'method' => 'GET',
            'route' => ['organization.index'],
            'class' => 'form-horizontal',
            'role' => 'form',
        ]) !!}
        <div class="row">
            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Organization Name:</label>
                    <div class="input-group">

                        @php $org_name = isset(request()->name) ? request()->name : null;
                        @endphp
                        {!! Form::text('name', $value = $org_name, [
                            'id' => 'org_name',
                            'placeholder' => 'Enter Organization Name',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Phone:</label>
                    <div class="input-group">

                        @php $org_phone = isset(request()->phone) ? request()->phone : null;
                        @endphp
                        {!! Form::text('phone', $value = $org_phone, [
                            'id' => 'org_phone',
                            'placeholder' => 'Enter Phone',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Email:</label>
                    <div class="input-group">

                        @php $org_email = isset(request()->email) ? request()->email : null;
                        @endphp
                        {!! Form::text('email', $value = $org_email, [
                            'id' => 'org_email',
                            'placeholder' => 'Enter Email',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Address:</label>
                    <div class="input-group">

                        @php $org_address = isset(request()->address) ? request()->address : null;
                        @endphp
                        {!! Form::text('address', $value = $org_address, [
                            'id' => 'org_address',
                            'placeholder' => 'Enter Address',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
            <a href="{{ route('organization.index') }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
        </div>
    </div>
    {!! Form::close() !!}
</div>
<script>
    $('.select2').select2();
</script>
