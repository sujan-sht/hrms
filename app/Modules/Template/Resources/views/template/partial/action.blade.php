<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="form-group row">
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Template Type :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('template_type', $templateType->slug, [
                                        'placeholder' => 'Enter Template Type',
                                        'class' => 'form-control',
                                        'readonly',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Template Form:</label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::textarea('text', null, [
                                        'placeholder' => 'Enter Template Text',
                                        'id' => 'editor-full',
                                        'class' => 'form-control summernote',
                                    ]) !!}
                                </div>
                                @if ($errors->has('text'))
                                    <div class="error text-danger">{{ $errors->first('text') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="template_type_id" value="{{ $template_type_id }}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">

            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead>
                        <tr>
                            <th class="text-white" class="w-100">Title</th>
                            <th class="text-white">Slug</th>
                            <th class="text-white">Copy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cheatSheets as $cheatSheet)
                            <tr class="parent">
                                <td>{{ $cheatSheet->title }}</td>
                                <td>
                                    <span class="text-muted font-size-sm copyitem">{{ $cheatSheet->short_code }}</span>
                                </td>
                                <td>
                                    <h6 class="text-primary copyToClipboard"
                                        data-shortcode="{{ $cheatSheet->short_code }}"><i class="icon-copy"></i></h6>
                                </td>
                            </tr>
                        @empty
                            <h5>No Cheat Sheets Found!</h5>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /daily sales -->
    </div>
</div>

<div class="text-center">
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

<script>
    $(document).on('click', '.copyToClipboard', function() {
        let shortcode = $(this).data('shortcode');
        navigator.clipboard.writeText(shortcode);
    });
</script>
