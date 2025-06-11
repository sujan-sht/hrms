@extends('admin::employee.layout')
@section('title')Claim @stop
@section('breadcrum')Request Claim @stop
@section('scripts')

    <script type="text/javascript">
        $('document').ready(function() {
            $('.delete_tada').on('click', function() {
                var link = $(this).attr('link');
                $('.get_link').attr('href', link);
            });
        });
    </script>
@stop

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')


    <div class="box">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2">
                    <div class="card-header">
                        <h6 class="float-left">Claim Request List</h6>
                        @if ($menuRoles->assignedRoles('employeetadaClaim.create'))
                            <a href="{{ route('employeetadaClaim.create') }}" class="btn btn-primary float-right text-white"
                                type="button"><i class="icon-plus2"></i> Add Claim</a>
                        @endif
                    </div>
                    <div class="card-body table-content">
                        <table class="table">
                            <tr class="bg-slate">
                                <th scope="col">Title</th>
                                <th scope="col">From Date</th>
                                <th scope="col">To Date</th>
                                <th scope="col">Request Amount</th>
                                <th scope="col">Status</th>
                                @if (
                                    $menuRoles->assignedRoles('employeetadaClaim.edit') ||
                                        $menuRoles->assignedRoles('employeetadaClaim.delete') ||
                                        $menuRoles->assignedRoles('employeetadaClaim.show') ||
                                        $menuRoles->assignedRoles('employeetadaClaim.edit'))
                                    <th scope="col">Action</th>
                                @endif
                            </tr>
                            @if ($tadas->total() > 0)
                                @foreach ($tadas as $tada)
                                    <tr>
                                        <td scope="row">{{ $tada->title }}</td>
                                        <td>{{ $tada->nep_from_date }}</td>
                                        <td>{{ $tada->nep_to_date }}</td>
                                        <td>Rs. {{ $tada->billAmount() ?? 0 }}</td>
                                        <td class="text-teal">
                                            {{ ucfirst($tada->status) }}
                                        </td>
                                        <!-- @if ($menuRoles->assignedRoles('employeetadaClaim.show'))
    -->
                                        <td>
                                            @if ($menuRoles->assignedRoles('employeetadaClaim.show'))
                                                <a class="mr-3" href="{{ route('employeetadaClaim.show', $tada->id) }}"
                                                    data-original-title="History">
                                                    <i class="icon-eye"></i> </a>
                                            @endif
                                            @if ($menuRoles->assignedRoles('employeetadaClaim.edit'))
                                                <a class="mr-3" href="{{ route('employeetadaClaim.edit', $tada->id) }}"
                                                    data-original-title="History" title="Edit">
                                                    <i class="fa fa-edit"></i></a>
                                            @endif
                                            @if ($menuRoles->assignedRoles('employeetadaClaim.delete') && $tada->status == 'pending')
                                                <a class="delete delete_tada" data-toggle="modal"
                                                    data-target="#modal_theme_warning"
                                                    link="{{ route('employeetadaClaim.delete', $tada->id) }}"
                                                    data-placement="bottom" data-popup="tooltip" title="Delete">
                                                    <i class="fa fa-trash"></i></a>
                                            @endif
                                        </td>
                                        <!--
    @endif -->

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9">
                                        <div
                                            class="d-flex flex-column justify-content-center align-items-center text-center h-100 pt-3">
                                            <svg width="20" height="20" fill="#ccc" viewBox="0 -14 512 512"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M136.965 308.234c4.781-2.757 6.418-8.879 3.66-13.66-2.762-4.777-8.879-6.418-13.66-3.66-4.781 2.762-6.422 8.883-3.66 13.66 2.757 4.781 8.879 6.422 13.66 3.66zm0 0" />
                                                <path
                                                    d="M95.984 377.254l50.36 87.23c10.867 18.844 35.312 25.82 54.644 14.645 19.13-11.055 25.703-35.496 14.637-54.64l-30-51.97 25.98-15c4.782-2.765 6.422-8.878 3.66-13.66l-13.003-22.523c1.55-.3 11.746-2.3 191.539-37.57 22.226-1.207 35.543-25.516 24.316-44.95l-33.234-57.562 21.238-32.168a10.004 10.004 0 00.317-10.512l-20-34.64a10.02 10.02 0 00-9.262-4.98l-38.473 2.308-36.894-63.907c-5.344-9.257-14.918-14.863-25.606-14.996-.129-.004-.254-.004-.383-.004-10.328 0-19.703 5.141-25.257 13.832L119.93 202.602l-84.926 49.03C1.602 270.91-9.97 313.763 9.383 347.255c17.68 30.625 54.953 42.672 86.601 30zm102.325 57.238c5.523 9.555 2.254 21.781-7.329 27.317-9.613 5.558-21.855 2.144-27.316-7.32l-50-86.614 34.64-20c57.868 100.242 49.075 85.012 50.005 86.617zm-22.684-79.297l-10-17.32 17.32-10 10 17.32zm196.582-235.91l13.82 23.938-12.324 18.664-23.82-41.262zM267.289 47.152c2.684-4.39 6.941-4.843 8.668-4.797 1.707.02 5.961.551 8.527 4.997l116.313 201.464c3.789 6.559-.817 14.805-8.414 14.993-1.363.03-1.992.277-5.485.93L263.863 51.632c2.582-3.32 2.914-3.64 3.426-4.48zm-16.734 21.434l115.597 200.223-174.46 34.218-53.047-91.879zM26.703 337.254a49.933 49.933 0 01-6.71-24.95c0-17.835 9.585-34.445 25.01-43.35l77.942-45 50 86.6-77.941 45.005c-23.879 13.78-54.516 5.57-68.3-18.305zm0 0" />
                                                <path
                                                    d="M105.984 314.574c-2.761-4.781-8.879-6.422-13.66-3.66l-17.32 10c-4.774 2.758-10.902 1.113-13.66-3.66-2.762-4.781-8.88-6.422-13.66-3.66s-6.422 8.879-3.66 13.66c8.23 14.258 26.59 19.285 40.98 10.98l17.32-10c4.781-2.761 6.422-8.875 3.66-13.66zm0 0M497.137 43.746l-55.723 31.008c-4.824 2.687-6.562 8.777-3.875 13.601 2.68 4.82 8.766 6.567 13.602 3.875l55.718-31.007c4.829-2.688 6.563-8.778 3.875-13.602-2.683-4.828-8.773-6.562-13.597-3.875zm0 0M491.293 147.316l-38.637-10.351c-5.336-1.43-10.82 1.734-12.25 7.07-1.43 5.336 1.739 10.817 7.074 12.246l38.641 10.352c5.367 1.441 10.824-1.774 12.246-7.07 1.43-5.336-1.738-10.82-7.074-12.247zm0 0M394.2 7.414l-10.364 38.64c-1.43 5.337 1.734 10.817 7.07 12.25 5.332 1.426 10.817-1.73 12.25-7.07l10.36-38.64c1.43-5.336-1.735-10.82-7.07-12.25-5.333-1.43-10.817 1.734-12.247 7.07zm0 0" />
                                            </svg>
                                            <p class="text-muted pt-1 mb-0">No Data Found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                        </table>
                        <span style="margin: 5px;float: right;">
                            @if ($tadas->total() != 0)
                                {!! $tadas->appends(\Request::except('page'))->render() !!}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="modal_view_request" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h6 class="modal-title">Tada Details</h6>
                </div>

                <div class="modal-body">
                    <div class="table-responsive result_view_detail">

                    </div><!-- table-responsive -->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn bg-teal-400" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Warning modal -->

    <!-- Warning modal -->
    <div id="modal_theme_warning" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h6 class="modal-title">Are you sure to Delete a Tada Request ?</h6>
                </div>

                <div class="modal-body">
                    <a class="btn btn-success get_link" href="">Yes</a> &nbsp; | &nbsp;
                    <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                </div>

                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    <!-- /warning modal -->


@endsection
