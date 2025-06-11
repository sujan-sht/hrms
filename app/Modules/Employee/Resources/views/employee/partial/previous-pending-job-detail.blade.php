@extends('admin::layout')
@section('title')Previous Pending Job Detail @stop
@section('breadcrum')
    <a href="{{ route('employee.index') }}" class="breadcrumb-item">Employees</a>
    <a class="breadcrumb-item active">Previous Pending Job Detail</a>
@stop


@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Employee</th>
                    <th>Company Name</th>
                    <th>Functional Title</th>
                    <th>Industry Type</th>
                    <th>Role Key</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Approved By HR</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($previous_job_details as $key => $item)
                    <tr>
                        <td width="5%">#{{ ++$key }}</td>
                        <td>{{ optional($item->employee)->full_name }}</td>
                        <td>{{ $item->company_name }}</td>
                        <td>{{ $item->job_title }}</td>
                        <td>{{ $item->industry_type }}</td>
                        <td>{{ $item->role_key }}</td>
                        <td>{{ $item->from_date }}</td>
                        <td>{{ $item->to_date }}</td>
                        <td>{{ $item->approved_by_hr == 1 ? 'Yes' : 'No' }}</td>
                        <td class="text-center">
                            {{-- <a class="btn btn-outline-secondary btn-icon mx-1" data-toggle="modal"
                                data-target=".bd-example-modal-lg" data-popup="tooltip" data-placement="top"
                                data-original-title="View">
                                <i class="icon-eye"></i>
                            </a> --}}
                            @if ($item->approved_by_hr == 0)
                                <a class="btn btn-outline-warning btn-icon mr-1 updateStatusClick" data-toggle="modal"
                                    data-target="#updateStatus" data-id="{{ $item->id }}" data-placement="bottom"
                                    data-popup="tooltip" data-original-title="Update Status">
                                    <i class="icon-flag3"></i>
                                </a>
                            @endif


                        </td>
                    </tr>


                    {{-- <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                            </div>
                        </div>
                    </div> --}}
                @empty
                    <tr>
                        <td colspan="5">No PreviousJob Details Found !!!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div id="updateStatus" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Update Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open([
                        'route' => 'employee.updatePendingPreviousJobDetail',
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                    ]) !!}
                    {!! Form::hidden('id', null, ['class' => 'jobDetailId']) !!}

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Status :</label>
                        <div class="col-lg-9">
                            {!! Form::select('status', $statusList, null, ['id' => 'attendanceStatus', 'class' => 'form-control select2']) !!}
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn bg-success text-white">Save Changes</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@stop

@section('script')
    <script>
        $(document).ready(function() {
            $('.updateStatusClick').on('click', function() {
                let id = $(this).data('id');
                $('.jobDetailId').val(id);
            });
        });
    </script>
@endsection
