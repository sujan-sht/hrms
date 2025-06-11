<div id="modal_default_import" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-grey">
                <h5 class="modal-title font-weight-black ">Import</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="bd-import">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Upload Payroll Static Data</h4>
                                <form method="POST" action="{{ route('payroll.uploadPayrollStaticData') }}" accept-charset="UTF-8" class="form-horizontal" role="form" enctype="multipart/form-data" id="employee-form">
                                    @csrf
                                    <div class="position-relative">
                                        <input type="file" class="form-control h-auto" name="upload_payroll_static_data">
                                    </div>
                                    <span class="form-text text-muted">Accepted formats: xls, xlsx</span>
                                    <button type="submit" class="text-light btn bg-primary btn-labeled btn-labeled-left"><b><i class="icon-upload"></i></b>Upload</button>
                                </form>
                                <div class="mt-3 form-group row list-items-producds/employee_sample/sample_employee.xt alert alert-success" style="border: dashed;border-radius: 25px;border-width: thin;padding: 7px;">
                                    <p class="mt-1"><b>Note:</b> Please make sure that Before Uploading Payroll DataSheet, Please Make Sure, You have Correct and Accurate Employee Data Formate as Similar to Sample Employee DataSheet. Data may not be uploaded if missed Any Required Data.</p>
                                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-0 text-light bg-secondary">
                                <div class="card-body text-center">
                                    <i class="icon-file-spreadsheet icon-2x border-3 rounded-round p-3 mb-3 mt-1"></i>
                                    <h6>Get Payroll Data Sample Sheet</h6>
                                    <p>
                                        Before Uploading Payroll Data - Please make sure, You have correct Payroll Data Format as similar to Sample Employee Data.
                                    </p>
                                     <a href="{{route('payroll.exportStaticData',['payroll_id' => $payrollModel->id])}}" target="_blank" class="text-light btn bg-primary btn-labeled btn-labeled-left"><b><i class="icon-download4"></i></b>Download</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>