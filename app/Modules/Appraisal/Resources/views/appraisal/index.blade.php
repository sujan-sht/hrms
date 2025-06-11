@extends('admin::layout')
@section('title') Score @stop

@section('breadcrum')
    <a href="{{ route('score.index') }}" class="breadcrumb-item">Score</a>
    <a class="breadcrumb-item active">List</a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@stop

@section('content')
    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Scores</h6>
                You can either click <b class="text-white badge badge-secondary"><i>Enter  ↳</i></b> &nbsp; or <b class="text-white badge badge-secondary"><i>tab</i></b>  &nbsp; button to update data in below form.
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>Score</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                </tr>
            </thead>
            <tbody>
                <form>
                    @for ($i = 0; $i < 3; $i++)
                        <tr>
                            <td>{{ $fields[$i] }}</td>
                            @if ($fields[$i] == 'Frequency')
                                @foreach ($scores as $key => $score)
                                    <td><input type="text" value="{{ $frequencies[$key] }}"
                                            class="form-control fieldChange" data-score="{{ $score }}"
                                            autocomplete="off" data-fieldtype="frequency"></td>
                                @endforeach
                            @endif
                            @if ($fields[$i] == 'Ability')
                                @foreach ($scores as $key => $score)
                                    <td><input type="text" value="{{ $abilities[$key] }}"
                                            class="form-control fieldChange" data-score="{{ $score }}"
                                            data-fieldtype="ability" autocomplete="off"></td>
                                @endforeach
                            @endif
                            @if ($fields[$i] == 'Effectiveness')
                                @foreach ($scores as $key => $score)
                                    <td><input type="text" value="{{ $effectiveness[$key] }}"
                                            class="form-control fieldChange" data-score="{{ $score }}"
                                            data-fieldtype="effectiveness" autocomplete="off"></td>
                                @endforeach
                            @endif
                        </tr>
                    @endfor
                </form>
            </tbody>

        </table>

    </div>
    </div>

    <script>
        $('.fieldChange').on('change', function() {

            let score = $(this).data('score')
            let fieldtype = $(this).data('fieldtype')
            let field_value = $(this).val()

            let formData = {
                score,
                fieldtype,
                field_value,
                "_token": "{{ csrf_token() }}"
            };
            $.ajax({
                type: 'POST',
                url: "{{ route('score.update') }}",
                data: formData,
                success: function(resp) {
                    if (resp.status == 1) {
                        toastr.success(resp.message);
                        return
                    }
                    toastr.error(resp.message);
                    return
                }
            });

        })
    </script>

@endsection
