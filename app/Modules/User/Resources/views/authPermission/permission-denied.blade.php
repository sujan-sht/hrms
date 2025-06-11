@extends(( auth()->user()->user_type == 'employee' ? 'admin::employee.layout' : 'admin::layout' ))

@section('title') Permission Denied!! @endsection
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card card-body border-top-danger">
            <div class="text-center">
                <i class="icon-alert icon-2x text-danger-400 border-danger-400 border-3 rounded-round p-3 mb-3 mt-1"></i>
                <h2 class="m-0 font-weight-semibold">Permission Denied</h2>
                <p class="text-muted mb-3">Oops, an error has occurred. Not Authorised User !</p>

                <button onclick="window.history.go(-1); return false;" type="button" class="btn bg-warning-400 btn-labeled btn-labeled-left"><b><i class="icon-reading"></i></b> Go Back</button>
            </div>
        </div>
    </div>
</div>

@endsection
