<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="form-group row">

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Competancy :<span class="text-danger"> *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('name', null, ['placeholder' => 'Enter Name', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('name'))
                                    <div class="error text-danger">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                @include('appraisal::competency.partial.questions')
            </div>
        </div>
    </div>
</div>


<div class="text-center">
    <button type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left float-right"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

<script>
    $(document).ready(function() {
      $(".addMore").click(function() {
            $.ajax({
                url: "<?php echo route('appraisalQuestion.getRepeaterForm'); ?>",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    $(".repeaterForm").append(data.result);
                    $(".select-search").select2();
                    $('.numeric').keyup(function() {
                        if (this.value.match(/[^0-9.]/g)) {
                            this.value = this.value.replace(/[^0-9.]/g, '');
                        }
                    });
                }
            });
        });
    });
</script>
