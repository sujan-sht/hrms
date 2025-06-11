<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
{{-- <script src="{{asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script> --}}
{{-- <script src="{{asset('admin/global/js/demo_pages/form_multiselect.js')}}"></script> --}}

<div class="card">
    <div class="card-body">
        <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
        <div class="form-group row">
            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Organization :<span class="text-danger"> *</span></label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::select(
                                'organization_id',
                                $organizations,
                                @$unit->organization_id,
                                ['placeholder' => 'Select Branch', 'class' => 'form-control select-search organization_id','required'=>true]
                            ) !!}
                        </div>
                        @if($errors->has('organization_id'))
                            <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Branch :<span class="text-danger"> *</span></label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::select(
                                'branch_id',
                                [],
                                @$unit->branch_id,
                                ['placeholder' => 'Select Branch', 'class' => 'form-control select-search branch_id','required'=>true]
                            ) !!}
                        </div>
                        @if($errors->has('branch_id'))
                            <div class="error text-danger">{{ $errors->first('branch_id') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Title :<span class="text-danger"> *</span></label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-office"></i></span>
                            </span>
                            {!! Form::text('title', @$unit->title, ['placeholder'=>'Enter Title','class'=>'form-control','required'=>true]) !!}
                        </div>
                        @if($errors->has('title'))
                            <div class="error text-danger">{{ $errors->first('title') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-3">Status :<span class="text-danger"> *</span></label>
                    <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            {!! Form::select(
                                'status',
                                $statuses,
                                @$unit->status,
                                ['placeholder' => 'Select Status', 'class' => 'form-control select-search']
                            ) !!}
                        </div>
                        @if($errors->has('status'))
                            <div class="error text-danger">{{ $errors->first('status') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('scripts')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js')}}"></script>
    <script src="{{ asset('admin/validation/branch.js')}}"></script>
 
@endsection
