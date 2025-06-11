@extends('admin::layout')
@section('title') Clearance Responsibles @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Clearance Responsibles</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @include('offboarding::clearance.partial.advance_filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Clearance Responsibles</h6>
                All the OffBoard Clearance Information will be listed below. You can Create and Modify the data.
            </div>

        </div>
    </div>

    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Organization</th>
                        <th>Employee</th>

                    </tr>
                </thead>
                <tbody>

                        @foreach($clearanceModel->clearanceResponsible as $key => $value)
                            <tr>
                                <td width="5%">#{{ $loop->iteration}}</td>
                                <td>{{ optional($value->organization)->name}}</td>
                                {{-- <td>{{$value->employee_id}}</td> --}}
                                <td>{{ optional($value->employee)->getFullName() }}</td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{-- {{ $clearanceModels->appends(request()->all())->links() }} --}}
            </span>
        </div>
    </div>

@endsection

@section('script')
<script src="{{asset('admin/global/js/plugins/forms/styling/uniform.min.js')}}"></script>
<script src="{{asset('admin/global/js/demo_pages/form_inputs.js')}}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js')}}"></script>
@endSection
