@extends('admin::layout')
@section('title') Skill Setup @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Skill Setup </a>
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
                <h6 class="media-title font-weight-semibold">List of Skill Setup</h6>
                All the skill setup Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('skillSetup.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('skillSetup.create') }}" class="btn btn-success rounded-pill">Add Skill</a>
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
                            <th>Category</th>
                            <th>Daily Wage</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($skills->count() > 0)
                            @foreach ($skills as $key => $skill)


                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $skill->category }}</td>
                                    <td>{{ $skill->daily_wage }}
                                    </td>


                                        <td class="d-flex">
                                            @if ($menuRoles->assignedRoles('skillSetup.edit'))
                                                <a class="btn btn-outline-primary btn-icon mx-1"
                                                    href="{{ route('skillSetup.edit', $skill->id) }}" data-popup="tooltip"
                                                    data-placement="bottom" data-original-title="Edit">
                                                    <i class="icon-pencil7"></i>
                                                </a>
                                            @endif
                                            @if ($menuRoles->assignedRoles('skillSetup.delete'))
                                                <a class="btn btn-outline-danger btn-icon confirmDelete mx-1"
                                                    link="{{ route('skillSetup.delete', $skill->id) }}" data-placement="bottom"
                                                    data-popup="tooltip" data-original-title="Delete">
                                                    <i class="icon-trash-alt"></i>
                                                </a>
                                            @endif
                                        </td>
                                </tr>
                            @endforeach

                        @endif
                    </tbody>
                </table>
                {{-- <span style="margin: 5px;float: right;">
                    @if ($tadas->total() != 0)
                        {{ $tadas->links() }}
                    @endif
                </span> --}}
            </div>
        </div>
    </div>

@endsection
