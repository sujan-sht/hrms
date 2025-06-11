<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Details</legend>
                <div class="form-group row">
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Title :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('title', null, ['rows' => 5, 'placeholder' => 'Write title here..', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('title'))
                                    <div class="error text-danger">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Status :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('status', ['1' => 'Active', '0' => 'In-Active'], null, [
                                        'id' => 'status',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                                @if ($errors->has('status'))
                                    <div class="error text-danger">{{ $errors->first('status') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Type :<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('type', ['1' => 'Claim', '0' => 'Request'], null, [
                                        'id' => 'type',
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Type',
                                    ]) !!}
                                </div>
                                @if ($errors->has('type'))
                                    <div class="error text-danger">{{ $errors->first('type') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <legend class="text-uppercase font-size-sm font-weight-bold">TADA Sub Type</legend>

                @include('tada::tadaType.partial.subTypes')
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>


<script>
    $(document).ready(function() {
        $(".addMore").click(function() {
            $.ajax({
                url: "<?php echo route('tadaType.addMoreSubType'); ?>",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    $(".repeaterForm").append(data.result);
                }
            });
        });
    });
</script>
