@extends('admin::layout')
@section('title') {{$title}} @stop

@section('breadcrum')
    <a href="{{ route('bulkupload.attendanceLog') }}" class="breadcrumb-item">AttendanceOT Detail</a>
    <a class="breadcrumb-item active">Bulk Upload</a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@stop

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
    <div class="card card-body">
        <div class="bd-import">
            <div class="row">
                <div class="col-md-6 px-4">
                    <h4>Upload Attendance OT Detail Data Sheet <span class="text-danger">*</span></h4>
                    <form method="POST" action="{{ route('bulkupload.uploadAttendanceOverStay') }}" accept-charset="UTF-8"
                        class="form-horizontal" role="form" enctype="multipart/form-data" id="employee-form">
                        @csrf
                        <div class="position-relative">
                            <input type="file" class="form-control h-auto" name="upload_atd_over_stay" accept=".xlx, .xlsx" required>
                        </div>
                        <span class="form-text text-muted">Accepted formats: xls, xlsx</span>
                        <button type="submit" class="text-light btn bg-primary btn-labeled btn-labeled-left"><b><i
                                    class="icon-upload"></i></b>Upload</button>
                    </form>
                    <div class="mt-3 form-group row list-items-producds/employee_sample/sample_employee.xt alert alert-success"
                        style="border: dashed;border-radius: 25px;border-width: thin;padding: 7px;">
                        <p class="mt-1"><b>Note:</b> Please make sure that Before Uploading Attedance OT Detail DataSheet,
                            Please Make Sure, You have Correct and Accurate Data Formate as Similar to Sample Employee
                            Attedance OT DataSheet. Data may not be uploaded if missed Any Required Data.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-0 text-light bg-secondary">
                        <div class="card-body text-center">
                            <i class="icon-file-spreadsheet icon-2x border-3 rounded-round p-3 mb-3 mt-1"></i>
                            <h6>Get Employee Attendance OT Data Sample Sheet</h6>
                            <p>
                                Before Uploading Attendance OT Detail Data - Please make sure, You have correct Employee
                                AttendanceOT Detail Data Format as similar to Sample Employee Data.
                            </p>
                            <a href="{{ asset('samples/Attendance_Over_Stay_Sample.xlsx/') }}" target="_blank"
                                class="text-light btn bg-primary btn-labeled btn-labeled-left"><b><i
                                        class="icon-download4"></i></b>Download</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
