@extends('admin::layout')
@section('title') All Loan @endSection
@section('breadcrum')
<a class="breadcrumb-item active">All Loan</a>
@stop

@section('script')
<script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
@endSection


@section('content')


@include('loan::partial.advance-filter-loan')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Loan</h6>
            All the Loan Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1">
            <a href="{{ route('loan.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add</a>
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
                    <th>Title</th>
                    <th>Description</th>
                    <th>Total Days</th>
                    <th>Leave Type</th>
                    <th>Gender</th>
                    <th>Marital Status</th>
                    <th>Status</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7">No Record Found !!!</td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{-- {{ $leaveTypeModels->appends(request()->all())->links() }} --}}
        </span>
    </div>
</div>
@endsection
