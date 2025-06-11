<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open([
            'method' => 'GET',
            'route' => ['template.index'],
            'class' => 'form-horizontal',
            'role' => 'form',
        ]) !!}
        <div class="row">
            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Template Title:</label>
                    <div class="input-group">

                        @php $template_title = isset(request()->title) ? request()->title : null;
                        @endphp
                        {!! Form::text('title', $value = $template_title, [
                            'id' => 'template_title',
                            'placeholder' => 'Enter Template Title',
                            'class' => 'form-control',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                    <label class="d-block font-weight-semibold">Select Template Type:</label>
                    @php $template_type = isset(request()->template_type) ? request()->template_type : null; @endphp
                    {!! Form::select('template_type', $templateTypesList, $value = $template_type, [
                        'id' => 'template_type',
                        'placeholder' => 'Select Template Type',
                        'class' => 'form-control select2',
                    ]) !!}

                    <div class="input-group">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-end mt-2">
        <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
        <a href="{{ route('template.index') }}" class="btn bg-secondary text-white"><i class="icons icon-reset mr-1"></i>Reset</a>
    </div>


    {!! Form::close() !!}
</div>
<script>
    $('.select2').select2();
</script>
