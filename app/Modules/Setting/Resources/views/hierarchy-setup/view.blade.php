@extends('admin::layout')
@section('title') Hierarchy @endSection
@section('breadcrum')
<a href="{{ route('hierarchySetup.index') }}" class="breadcrumb-item">Hierarchies</a>
<a class="breadcrumb-item active">View</a>
@stop

@section('content')

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <legend class="text-uppercase font-size-sm font-weight-bold">Organization</legend>
                <ul class="media-list">
                    <li class="media mt-2">
                        <span
                            class="font-weight-semibold mr-3">{{ optional($hierarchySetupModel->getOrganization)->name }}</span>
                    </li>
                </ul>

                <br>
                <legend class="text-uppercase font-size-sm font-weight-bold">List of Sub-Function</legend>
                <ul class="media-list">
                    @foreach ($hierarchySetupModel->getOrganizationDepartments as $key => $getOrganizationDepartment)
                        <li class="media mt-2">
                            <span class="font-weight-semibold mr-3">{{ ++$key }}.</span>
                            <span>{{ $getOrganizationDepartment->department_name }}</span>
                        </li>
                    @endforeach
                </ul>

                <br>
                <legend class="text-uppercase font-size-sm font-weight-bold">List of Level</legend>
                <ul class="media-list">
                    @foreach ($hierarchySetupModel->getOrganizationLevels as $key => $getOrganizationLevel)
                        <li class="media mt-2">
                            <span class="font-weight-semibold mr-3">{{ ++$key }}.</span>
                            <span>{{ $getOrganizationLevel->level_name }}</span>
                        </li>
                    @endforeach
                </ul>

                <br>
                <legend class="text-uppercase font-size-sm font-weight-bold">List of Designation</legend>
                <ul class="media-list">
                    @foreach ($hierarchySetupModel->getOrganizationDesignations as $key => $getOrganizationDesignation)
                        <li class="media mt-2">
                            <span class="font-weight-semibold mr-3">{{ ++$key }}.</span>
                            <span>{{ $getOrganizationDesignation->designation_name }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
</div>

@endSection
