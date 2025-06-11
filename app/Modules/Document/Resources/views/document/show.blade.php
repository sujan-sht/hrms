@extends('admin::layout')
@section('title')
    View Document
@endsection
@section('breadcrum')
    <a href="{{ route('document.index') }}" class="breadcrumb-item">Documents</a>
    <a class="breadcrumb-item active">View</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@section('content')
    {{-- {!! Form::model($documentModel,['method'=>'PUT','route'=>['document.update',$documentModel->id],'class'=>'form-horizontal','id'=>'documentFormSubmit','role'=>'form','files'=>true]) !!} --}}

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <legend class="text-uppercase font-size-sm font-weight-bold">Document Detail</legend>
                            <ul class="media-list">
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Created Date :</span>
                                    <div class="ml-2">{{ date('M d, Y', strtotime($documentModel->created_at)) }}</div>
                                </li>
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Title :</span>
                                    <div class="ml-2">{{ $documentModel->title }}</div>
                                </li>
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Document Type :</span>
                                    <div class="ml-2">{{ ucfirst($documentModel->type) ?? null }}</div>
                                </li>

                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Description :</span>
                                    <div class="ml-2">{{ $documentModel->description }}</div>
                                </li>
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Status :</span>
                                    <div class="ml-2 badge badge-{{ $documentModel->getStatusWithColor()['color'] }}">
                                        {{ $documentModel->getStatusWithColor()['status'] }}</div>
                                </li>
                            </ul>
                        </div>

                        @if (count($documentModel->attachments) > 0)
                            <div class="col-md-6">
                                <legend class="text-uppercase font-size-sm font-weight-bold">Attachments</legend>
                                <table class="table mt-0">
                                    @foreach ($documentModel->attachments as $file)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-3">
                                                        <a class="btn btn-teal rounded-pill btn-icon">
                                                            <span class="">{{ strtoupper($file->extension) }}</span>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <a href="{{ $file->attachment }}" target="_blank"
                                                            class="text-body font-weight-semibold letter-icon-title">{{ $file->title }}</a>
                                                        <div class="text-muted font-size-sm">{{ $file->getSize() }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @if (auth()->user()->user_type == 'hr' ||
                                auth()->user()->user_type == 'super_admin' ||
                                auth()->user()->user_type == 'admin')
                            <div class="col-md-6">
                                <legend class="text-uppercase font-size-sm font-weight-bold">Organization</legend>
                                <ul class="media-list">
                                    <li class="media mt-2">
                                        <span class="font-weight-semibold">Name :</span>
                                        <div class="ml-2">
                                            {{ optional(optional($documentModel->documentOrganization)->Organization)->name }}
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        @endif
                        @if ($documentModel->method_type == 1)
                            @if (count(optional($documentModel->documentOrganization)->documentOrganizationDepartment) > 0)
                                <div class="col-md-6">
                                    <legend class="text-uppercase font-size-sm font-weight-bold">Department Name</legend>
                                    <ul class="media-list">
                                        @php $i = 1; @endphp
                                        @foreach (optional($documentModel->documentOrganization)->documentOrganizationDepartment as $docDepartment)
                                            <li class="media mt-2">
                                                <span class="font-weight-semibold">{{ $i }} :</span>
                                                <div class="ml-2">{{ optional($docDepartment->department)->title }}</div>
                                            </li>
                                            @php $i++;  @endphp
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @else
                            @if (count($documentModel->documentEmployee) > 0)
                                <div class="col-md-6">
                                    <legend class="text-uppercase font-size-sm font-weight-bold">Employee Name</legend>
                                    <ul class="media-list">
                                        @php $i = 1; @endphp
                                        @foreach ($documentModel->documentEmployee as $docEmp)
                                            <li class="media mt-2">
                                                <span class="font-weight-semibold">{{ $i }} :</span>
                                                <div class="ml-2">{{ optional($docEmp->Employee)->full_name }}</div>
                                            </li>
                                            @php $i++;  @endphp
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- {!! Form::close() !!} --}}

@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // initiate select2
            $('.select-search').select2();

            // $('#leaveStatus').on('change', function() {
            //     var status = $(this).val();
            //     if(status == '2' || status == '4') {
            //         $('#statusMessage').show();
            //     } else {
            //         $('#statusMessage').hide();
            //     }
            // });
        });
    </script>
@endsection
