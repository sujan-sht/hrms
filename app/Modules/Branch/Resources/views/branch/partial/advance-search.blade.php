<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_multiselect.js')}}"></script>

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    {!! Form::open([
        'method' => 'GET',
        'route' => ['branch.index'],
        'class' => 'form-horizontal',
        'role' => 'form',
    ]) !!}
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                        <label class="d-block font-weight-semibold">Organization</label>
                        <div class="input-group">
                            {!! Form::select(
                                'organization_id',
                                $organizationList,
                                $value = count($organizationList) === 1 ? array_key_first($organizationList->toArray()) : request('organization_id'),
                                ['placeholder' => 'Select Organization', 'class' => 'form-control select2']
                            ) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Province</label>
                    {!! Form::select('id[]', $province ?? [], $value = request('id') ? : null, [
                        'placeholder' => 'Select Province',
                        'class'=>'form-control select-search',
                        'id' => 'provinceSelect'
                    ]) !!}
                </div>


                <div class="col-md-3 mb-2">
                    <label class="form-label">District</label>
                    {!! Form::select('district_id[]', $districtList ?? [], $value = request('district_id') ? : null, [
                        'placeholder' => 'Select District',
                        'class'=>'form-control multiselect-filtering',
                        'multiple' => 'multiple',
                        'id' => 'districtSelect'
                    ]) !!}
                </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
                <a href="{{ request()->url() }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
            </div>
        </div>
    {!! Form::close() !!}
</div>

<script>
    $('.select2').select2();
</script>
