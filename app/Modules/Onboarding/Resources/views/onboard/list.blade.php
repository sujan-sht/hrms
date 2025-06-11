@extends('admin::layout')
@section('title') Onboards @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Onboards</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

@include('onboarding::onboard.partial.advance_filter')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Onboards</h6>
            All the MRF Information will be listed below. You can Create and Modify the data.
        </div>
        <!-- <div class="mt-1">
                <a href="{{ route('onboard.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add</a>
            </div> -->
    </div>
</div>

<div class="card card-body">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr class="btn-slate text-light">
                    <th>S.N</th>
                    <th>MRF Title</th>
                    <th>Applicant</th>
                    <th>Onboarding</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if (count($applicantModels) != 0)
                    @php $count = 1; @endphp
                    @foreach ($applicantModels as $applicantModel)
                        <tr>
                            <td>#{{ $count++ }}</td>
                            <td>{{ optional($applicantModel->mrfModel)->title }}</td>
                            <td>{{ $applicantModel->getFullName() }}</td>
                            <!-- <td>
                                        @foreach ($preboardingtasks as $key => $preboard)
<p><i class="icons {{ in_array($preboard->id, $applicantModel->boarding) ? 'icon-checkmark-circle text-success' : 'icon-cancel-circle2  text-danger' }} pr-1"></i>
                                                 {{ $preboard->title }}</p>
@endforeach
                                    </td> -->
                            <td>
                                @foreach ($boardingtasks as $key => $boardingTask)
                                    <p><i
                                            class="icons {{ in_array($boardingTask->id, $applicantModel->boarding) ? 'icon-checkmark-circle text-success' : 'icon-cancel-circle2  text-danger' }} pr-1"></i>
                                        {{ $boardingTask->title }}</p>
                                @endforeach
                            </td>
                            <!-- <td>
                                        @foreach ($postboardingtasks as $key => $preboard)
<p><i class="icons {{ in_array($preboard->id, $applicantModel->boarding) ? 'icon-checkmark-circle text-success' : 'icon-cancel-circle2  text-danger' }} pr-1"></i>
                                                 {{ $preboard->title }}</p>
@endforeach
                                    </td> -->
                            <td>
                                @foreach ($boardingtasks as $key => $boardingTask)
                                    @php
                                        $status = $boardingTask->getStatusDetail(
                                            $applicantModel->id,
                                            optional($applicantModel->mrfModel)->id,
                                        );
                                        if ($status == '1') {
                                            $color = 'success';
                                            $text = 'Completed';
                                        } else {
                                            $color = 'secondary';
                                            $text = 'Pending';
                                        }
                                    @endphp
                                    <p><span class="badge badge-{{ $color }}">{{ $text }}</span></p>
                                @endforeach
                            </td>
                            <td>
                                @if ($menuRoles->assignedRoles('onboard.edit'))
                                    <a class="btn btn-sm btn-outline-primary btn-icon mr-1"
                                        href="{{ route('onboard.edit', ['mrf' => optional($applicantModel->mrfModel)->id, 'applicant' => $applicantModel->id]) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">No Record Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
@endSection
