<script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_multiselect.js')}}"></script>

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="form-label">Province</label>
                    {!! Form::select('id[]', $province ?? [], $request->input('id', []), [
                        'class'=>'form-control multiselect-filtering',
                        'id' => 'provinceSelect',
                        'multiple' => 'multiple'
                    ]) !!}
                </div>

                <div class="col-md-3 mb-2">
                    <label class="form-label">District</label>
                    {!! Form::select('district_id[]', $districtList ?? [], $request->input('district_id', []), [
                        'class'=>'form-control multiselect-filtering',
                        'id' => 'districtSelect',
                        'multiple' => 'multiple'
                    ]) !!}
                </div>

            </div>
            <div class="d-flex justify-content-end mt-4">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ request()->url() }}" class="btn bg-secondary text-white">
                    <i class="icons icon-reset mr-1"></i>Reset
                </a>
            </div>
        </form>

    </div>
</div>

<script>
    // $('.select2').select2();
</script>
