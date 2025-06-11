@extends('admin::layout')
@section('title') Labour KYE @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Labour KYE </a>
@endsection
@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>
@stop

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    {{-- @include('tada::partial.filter') --}}

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Labours</h6>
                All the Labour Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('labour.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('labour.create') }}" class="btn btn-success rounded-pill">Add Labour</a>
                </div>
            @endif

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th>#</th>
                            <th>Organization</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Pan No</th>
                            <th>Skill Type</th>
                            <th>Joined date</th>

                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($labours->count() > 0)
                            @foreach ($labours as $key => $labour)


                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $labour->organizationModel->name }}</td>
                                    <td>{{ $labour->first_name }}</td>
                                    <td>{{ $labour->middle_name }}</td>
                                    <td>{{ $labour->last_name }}</td>
                                    <td>{{ $labour->pan_no }}</td>
                                    <td>{{ $labour->skillType->category }}</td>

                                    <td>{{ $labour->join_date }}
                                    </td>


                                        <td class="d-flex">
                                            @if ($menuRoles->assignedRoles('labour.edit'))
                                                <a class="btn btn-outline-primary btn-icon mx-1"
                                                    href="{{ route('labour.edit', $labour->id) }}" data-popup="tooltip"
                                                    data-placement="bottom" data-original-title="Edit">
                                                    <i class="icon-pencil7"></i>
                                                </a>
                                            @endif
                                            @if ($menuRoles->assignedRoles('labour.delete'))
                                                <a class="btn btn-outline-danger btn-icon confirmDelete mx-1"
                                                    link="{{ route('labour.delete', $labour->id) }}" data-placement="bottom"
                                                    data-popup="tooltip" data-original-title="Delete">
                                                    <i class="icon-trash-alt"></i>
                                                </a>
                                            @endif

                                            @if ($menuRoles->assignedRoles('labour.archive') && ($labour->is_archived==0))
                                                <a  data-toggle="modal" data-target="#modal_archive_labour" class="btn btn-outline-warning text-warning btn-icon border-1 labour_archive_status" labour_id="{{$labour->id}}" data-popup="tooltip" data-placement="bottom"
                                                data-original-title="Move To Archive"><i class="icon-basket"></i></a>
                                            @endif
                                            @if ($menuRoles->assignedRoles('labour.active') && ($labour->is_archived==1))
                                                <a href="{{ route('labour.active',$labour->id) }}" class="btn btn-outline-success text-success btn-icon border-1" data-original-title="Move To Active"><i class="icon-thumbs-up3"></i></a>
                                            @endif
                                        </td>
                                </tr>
                            @endforeach

                        @endif
                    </tbody>
                </table>
                <span style="margin: 5px;float: right;">
                    {{ $labours->links() }}
                </span>
            </div>
        </div>
    </div>

    <div id="modal_archive_labour" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h6 class="modal-title">Are You Sure, You Want To Move This Labour into Archive State ?</h6>
                </div>
                    <script src="{{ asset('admin/global/js/plugins/pickers/pickadate/picker.js')}}"></script>
                    <script src="{{ asset('admin/global/js/demo_pages/picker_date.js')}}"></script>

                <div class="modal-body">
                    {!! Form::open(['route'=>'labour.archive','method'=>'POST','class'=>'form-horizontal','role'=>'form','files' => true]) !!}

                    <input type="hidden" class="labour_id" name="labour_id">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Archive Date: <span class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            {!! Form::text('archived_date', $value = null, ['id'=>'archive_date','placeholder'=>'Select Archive Date','class'=>'form-control daterange-single','required']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Reason:</label>
                        <div class="col-lg-9">
                            {!! Form::textarea('reason', $value = null, ['placeholder'=>'Enter Reason','class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Other Description:</label>
                        <div class="col-lg-9">
                            {!! Form::textarea('other_desc', $value = null, ['placeholder'=>'Other description','class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn bg-success">Move To Archive</button>
                        <button type="button" class="btn bg-danger" data-dismiss="modal">Close</button>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('document').ready(function () {

             $('.labour_archive_status').on('click', function () {
                var labour_id = $(this).attr('labour_id');
                console.log(labour_id);
                $('.labour_id').attr('value', labour_id);

            });
        });
    </script>
@endsection
