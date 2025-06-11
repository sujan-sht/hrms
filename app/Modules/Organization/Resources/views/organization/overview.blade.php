@extends('admin::layout')
@section('title')
    Organization Overview
@endSection
@section('breadcrum')
    <a href="{{ route('organization.index') }}" class="breadcrumb-item">Organizations</a>
    <a class="breadcrumb-item active">Overview</a>
@endSection

@section('content')

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="card bg-secondary text-white"
                        style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                        <div class="card-body text-center">
                            <div class="card-img-actions d-inline-block mb-3" style="width:150px; height:150px;">
                                <img class="img-fluid rounded-circle" src="{{ $organizationModel->getImage() }}"
                                    alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <h6 class="font-weight-semibold mb-0">{{ $organizationModel->name }}</h6>
                            <span class="d-block opacity-75">{{ $organizationModel->address }}</span>
                            <div class="ribbon-container">
                                {{-- <div class="ribbon bg-success">
                                <a class="text-light" href="" data-popup="tooltip" data-original-title="Employee Status"
                                    data-placement="bottom">Active</a>
                            </div> --}}
                            </div>
                        </div>
                    </div>
                    <legend class="text-uppercase font-size-sm font-weight-bold">Mission</legend>
                    <ul class="media-list">
                        <li class="media mt-2">
                            <div class="ml-auto">{{ $organizationModel->mission }}</div>
                        </li>
                    </ul>
                    <div class="pt-2">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Vision</legend>
                        <ul class="media-list">
                            <li class="media mt-2">
                                <div class="ml-auto">{{ $organizationModel->vision }}</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body pb-1">
                            <div class="row">
                                <div class="col-md-8">
                                    <h1 class="font-weight-semibold mb-0">{{ sprintf('%02d', $branchCount) }}</h1>
                                    <h5>Branches</h5>
                                </div>
                                <div class="col-md-4 text-right">
                                    <i class="icon-lan2 icon-3x text-secondary mt-1 mb-3"></i>
                                </div>
                            </div>
                        </div>
                        <img src="{{ asset('admin/widget-bg-secondary.png') }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body pb-1">
                            <div class="row">
                                <div class="col-md-8">
                                    <h1 class="font-weight-semibold mb-0">{{ sprintf('%02d', $departmentCount) }}</h1>
                                    <h5>Sub-Functions</h5>
                                </div>
                                <div class="col-md-4 text-right">
                                    <i class="icon-library2 icon-3x text-secondary mt-1 mb-3"></i>
                                </div>
                            </div>
                        </div>
                        <img src="{{ asset('admin/widget-bg-secondary.png') }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body pb-1">
                            <div class="row">
                                <div class="col-md-8">
                                    <h1 class="font-weight-semibold mb-0">{{ sprintf('%02d', $levelCount) }}</h1>
                                    <h5>Levels</h5>
                                </div>
                                <div class="col-md-4 text-right">
                                    <i class="icon-stack3 icon-3x text-secondary mt-1 mb-3"></i>
                                </div>
                            </div>
                        </div>
                        <img src="{{ asset('admin/widget-bg-secondary.png') }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body pb-1">
                            <div class="row">
                                <div class="col-md-8">
                                    <h1 class="font-weight-semibold mb-0">{{ sprintf('%02d', $employeeCount) }}</h1>
                                    <h5>Employees</h5>
                                </div>
                                <div class="col-md-4 text-right">
                                    <i class="icon-users2 icon-3x text-secondary mt-1 mb-3"></i>
                                </div>
                            </div>
                        </div>
                        <img src="{{ asset('admin/widget-bg-secondary.png') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <legend class="text-uppercase font-size-sm font-weight-bold">List of Branches</legend>
                    <div class="card card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="text-light btn-slate">
                                        <th>S.N</th>
                                        <th>Branch Name</th>
                                        <th>Location</th>
                                        <th>Contact</th>
                                        <th>Email</th>
                                        <th>Manager</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($branchModels->total() != 0)
                                        @foreach ($branchModels as $key => $branchModel)
                                            <tr>
                                                <td width="5%">#{{ $branchModels->firstItem() + $key }}</td>
                                                <td>{{ $branchModel->name }}</td>
                                                <td>{{ $branchModel->location }}</td>
                                                <td>{{ $branchModel->contact }}</td>
                                                <td>{{ $branchModel->email }}</td>
                                                <td>{{ optional($branchModel->managerEmployeeModel)->getFullName() }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">No Records Found !!!</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endSection
