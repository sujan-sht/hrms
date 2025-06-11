<div class="card card-body">
    {!! Form::open(['route' => 'vendor.index', 'method' => 'get']) !!}
    <div class="row">

         <div class="col-md-3">
            <label class="d-block font-weight-semibold">Vendor Group:</label>
            <div class="input-group">
                {!! Form::select('vendor_group_id[]', $vendorgroup, request('vendor_group_id') ?? null, ['class'=>'form-control multiselect-filtering', 'multiple']) !!}
            </div>
        </div>

         <div class="col-md-3">
            <label class="d-block font-weight-semibold">Vendor Code:</label>
            <div class="input-group">
                {!! Form::select('vendor_code[]', $vendor_code, request('vendor_code') ?? null, ['class'=>'form-control multiselect-filtering', 'multiple']) !!}
            </div>
        </div>

         <div class="col-md-3">
            <label class="d-block font-weight-semibold">Vendor Name:</label>
            <div class="input-group">
                {!! Form::select('vendor_name[]', $vendor_name, request('vendor_name') ?? null, ['class'=>'form-control multiselect-filtering', 'multiple']) !!}
            </div>
        </div>

         <div class="col-md-3">
            <label class="d-block font-weight-semibold">VAT/PAN:</label>
            <div class="input-group">
                 {!! Form::select('pan_vat_no[]', $pan_vat_no, request('pan_vat_no') ?? null, ['class'=>'form-control multiselect-filtering', 'multiple']) !!}
            </div>
        </div>

         <div class="col-md-3 mt-2">
            <label class="d-block font-weight-semibold">Rating:</label>
            <div class="input-group">
                  {!! Form::select('rating',[ 'Good'=>'Good','Moderate'=>'Moderate','Poor'=>'Poor'], $value = null, ['id'=>'rating','class'=>'form-control','placeholder'=>'Select Rating']) !!}
            </div>
        </div>

    </div>
    <div class="d-flex justify-content-end mt-2">
        <button class="btn bg-primary" type="submit">
            Search Now
        </button>
        <a href="{{ route('vendor.index') }}" data-popup="tooltip" data-placement="top" data-original-title="Refresh Search" class="btn bg-danger ml-2">
            <i class="icon-spinner9"></i>
        </a>
    </div>
    {!! Form::close() !!}
</div>


