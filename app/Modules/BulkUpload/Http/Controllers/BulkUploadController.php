<?php

namespace App\Modules\BulkUpload\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\Exports\UserDetailExport;
use App\Service\Import\ImportFile;
use App\Service\Import\UserImport;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\User\Entities\User;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Service\Import\HolidayImport;
use App\Service\Import\DarbandiImport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Attendance\Entities\Attendance;
use App\Modules\Branch\Entities\Branch;
use App\Modules\User\Repositories\UserRepository;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use App\Modules\BulkUpload\Service\Import\BranchImport;
use App\Modules\BulkUpload\Service\Import\LabourImport;
use App\Modules\Employee\Repositories\EmployeeRepository;
use App\Modules\BulkUpload\Service\Import\BankDetailImport;
use App\Modules\BulkUpload\Service\Import\LeaveDetailImport;
use App\Modules\BulkUpload\Service\Import\EmpBiometricImport;
use App\Modules\BulkUpload\Service\Import\FamilyDetailImport;
use App\Modules\BulkUpload\Service\Import\BenefitDetailImport;
use App\Modules\BulkUpload\Service\Import\MedicalDetailImport;
use App\Modules\BulkUpload\Service\Import\ContractDetailImport;
// For excel
use App\Modules\BulkUpload\Service\Import\DocumentDetailImport;
use App\Modules\BulkUpload\Service\Import\EmployeeDetailImport;
use App\Modules\BulkUpload\Service\Import\ResearchDetailImport;
use App\Modules\BulkUpload\Service\Import\TransferDetailImport;
use App\Modules\BulkUpload\Service\Import\CarrierMobilityImport;
use App\Modules\BulkUpload\Service\Import\EducationDetailImport;
use App\Modules\BulkUpload\Service\Import\EmergencyDetailImport;
use App\Modules\BulkUpload\Service\Import\PerformanceDetailImport;
use App\Modules\BulkUpload\Service\Import\PreviousJobDetailImport;
use App\Modules\BulkUpload\Service\Import\LeaveHistoryDetailImport;
use App\Modules\BulkUpload\Service\Import\AttendanceLogDetailImport;
use App\Modules\BulkUpload\Service\Import\VisaImmigrationDetailImport;
use App\Modules\BulkUpload\Service\Import\EmployeeJobDescriptionImport;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Designation;
use App\Modules\Setting\Entities\Level;

class BulkUploadController extends Controller
{

    public function branchDetail()
    {
        $data['title'] = 'Branch Detail';
        return view('bulkupload::bulkUpload.branch.index', $data);
    }

    public function uploadbranchDetail(Request $request)
    {
        // dd($request, 'branch');
        $files = $request->upload_family_detail;
        // dd($files);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        // $spreadsheet->getActiveSheet()->getStyle('G')
        //     ->getNumberFormat()
        //     ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        // $spreadsheet->getActiveSheet()->getStyle('H')
        //     ->getNumberFormat()
        //     ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        // dd($sheetData);
        array_shift($sheetData);

        $import_file = BranchImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Branch Detail Imported Successfully');
        }

        return redirect()->route('bulkupload.branch');
    }


    public function familyDetail()
    {
        $data['title'] = 'Family Detail';
        return view('bulkupload::bulkUpload.familyDetail.index', $data);
    }

    public function uploadfamilyDetail(Request $request)
    {
        $files = $request->upload_family_detail;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $spreadsheet->getActiveSheet()->getStyle('G')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);
        $import_file = FamilyDetailImport::import($sheetData);
        if ($import_file['success'] == true) {
            return redirect()->back()->with('success', $import_file['message']);
        } else {
            return redirect()->back()->with('error', $import_file['message']);
        }
        return redirect()->route('bulkupload.familyDetail');
    }

    public function emergencyDetail()
    {
        $data['title'] = 'Emergency Detail';
        return view('bulkupload::bulkUpload.emergencyDetail.index', $data);
    }

    public function uploadEmergencyDetail(Request $request)
    {
        // dd($request);
        $files = $request->upload_emergency_detail;
        // dd($files);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $spreadsheet->getActiveSheet()->getStyle('G')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $spreadsheet->getActiveSheet()->getStyle('H')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        // dd($sheetData);
        array_shift($sheetData);

        $import_file = EmergencyDetailImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Family Detail Imported Successfully');
        }

        return redirect()->route('bulkupload.emergencyDetail');
    }

    public function benefitDetail()
    {
        $data['title'] = 'Benefit Detail';
        return view('bulkupload::bulkUpload.benefitDetail.index', $data);
    }

    public function uploadBenefitDetail(Request $request)
    {
        // dd($request);
        $files = $request->upload_benefit_detail;
        // dd($files);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $spreadsheet->getActiveSheet()->getStyle('G')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        // `$spreadsheet->getActiveSheet()->getStyle('H')
        //     ->getNumberFormat()
        //     ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);`

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        // dd($sheetData);
        array_shift($sheetData);

        $import_file = BenefitDetailImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Family Detail Imported Successfully');
        }

        return redirect()->route('bulkupload.benefitDetail');
    }
    public function educationDetail()
    {
        $data['title'] = 'Education Detail';
        return view('bulkupload::bulkUpload.educationDetail.index', $data);
    }

    public function uploadEducationDetail(Request $request)
    {
        // dd($request);
        $files = $request->upload_education_detail;
        // dd($files);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $spreadsheet->getActiveSheet()->getStyle('G')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $spreadsheet->getActiveSheet()->getStyle('H')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);
        $import_file = EducationDetailImport::import($sheetData);
        if ($import_file['success']) {
            return redirect()->back()->with('success', $import_file['message']);
        } else {
            return redirect()->back()->with('error', $import_file['message']);
        }
        return redirect()->route('bulkupload.educationDetail');
    }

    public function previousJobDetail()
    {
        $data['title'] = 'Previous Job  Detail';
        return view('bulkupload::bulkUpload.previousJobDetail.index', $data);
    }
    public function uploadPreviousJobDetail(Request $request)
    {
        // dd($request);
        $files = $request->upload_job_detail;
        // dd($files);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $spreadsheet->getActiveSheet()->getStyle('F')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $spreadsheet->getActiveSheet()->getStyle('G')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        // dd($sheetData);
        array_shift($sheetData);

        $import_file = PreviousJobDetailImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Family Detail Imported Successfully');
        }

        return redirect()->route('bulkupload.previousJobDetail');
    }

    public function contractDetail()
    {
        $data['title'] = 'Contract Detail';
        return view('bulkupload::bulkUpload.contractDetail.index', $data);
    }
    public function uploadContractDetail(Request $request)
    {
        // dd($request);
        $files = $request->upload_contract_detail;
        // dd($files);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $spreadsheet->getActiveSheet()->getStyle('E')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $spreadsheet->getActiveSheet()->getStyle('F')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        // dd($sheetData);
        array_shift($sheetData);

        $import_file = ContractDetailImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Family Detail Imported Successfully');
        }

        return redirect()->route('bulkupload.contractDetail');
    }
    public function medicalDetail()
    {
        $data['title'] = 'Medical Detail';
        return view('bulkupload::bulkUpload.medicalDetail.index', $data);
    }
    public function uploadMedicalDetail(Request $request)
    {
        // dd($request);
        $files = $request->upload_medical_detail;
        // dd($files);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        // dd($sheetData);
        array_shift($sheetData);

        $import_file = MedicalDetailImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Family Detail Imported Successfully');
        }

        return redirect()->route('bulkupload.medicalDetail');
    }

    public function leaveDetail()
    {
        $data['title'] = 'Leave Detail';
        return view('bulkupload::bulkUpload.leaveDetail.index', $data);
    }
    public function uploadLeaveDetail(Request $request)
    {
        $files = $request->upload_leave_detail;
        // dd($files);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        // dd($sheetData);
        array_shift($sheetData);

        $import_file = LeaveDetailImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Leave Detail Imported Successfully');
        }


        return redirect()->route('bulkupload.leaveDetail');
    }

    public function leaveHistoryDetail()
    {
        $data['title'] = 'Leave History Detail';
        return view('bulkupload::bulkUpload.leaveHistoryDetail.index', $data);
    }
    public function uploadLeaveHistoryDetail(Request $request)
    {
        $files = $request->upload_leave_history_detail;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        // $spreadsheet->getActiveSheet()->getStyle('H')
        // ->getNumberFormat()
        // ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        // $spreadsheet->getActiveSheet()->getStyle('I')
        // ->getNumberFormat()
        // ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);
        $import_file = LeaveHistoryDetailImport::import($sheetData);

        if ($import_file['success']) {
            toastr()->success('Leave History Detail Imported Successfully');
        } else {
            // Loop through all errors and show them as toasts

            // foreach ($import_file['errors'] as $error) {
            //     toastr()->error($error);
            // }

            return $this->generateErrorReport($import_file['errors']);
        }

        return redirect()->route('bulkupload.leaveHistoryDetail');
    }

    protected function generateErrorReport(array $errors)
    {
        // Generate CSV content
        $csvContent = "Row Number,Error Message\n";
        foreach ($errors as $row => $errorMessages) {
            $csvContent .= $row . ',"' . implode("; ", (array)$errorMessages) . "\"\n";
        }

        // Generate filename with timestamp
        $filename = 'leave_import_errors_' . date('Ymd_His') . '.csv';

        // Return download response
        return response()->streamDownload(function () use ($csvContent) {
            echo $csvContent;
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function researchDetail()
    {
        $data['title'] = 'Research Detail';
        return view('bulkupload::bulkUpload.researchDetail.index', $data);
    }
    public function uploadResearchDetail(Request $request)
    {
        // dd($request);
        $files = $request->upload_research_detail;
        // dd($files);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        // dd($sheetData);
        array_shift($sheetData);

        $import_file = ResearchDetailImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Family Detail Imported Successfully');
        }

        return redirect()->route('bulkupload.researchDetail');
    }
    public function visaImmigrationDetail()
    {
        $data['title'] = 'Visa/Immigration Document Detail';
        return view('bulkupload::bulkUpload.visaImmigrationDetail.index', $data);
    }
    public function uploadVisaImmigrationDetail(Request $request)
    {
        $files = $request->upload_visa_immigration_detail;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $spreadsheet->getActiveSheet()->getStyle('F')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $spreadsheet->getActiveSheet()->getStyle('G')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        // dd($sheetData);
        array_shift($sheetData);

        $import_file = VisaImmigrationDetailImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Document Detail Imported Successfully');
        }

        return redirect()->route('bulkupload.visaImmigrationDetail');
    }

    public function documentDetail()
    {
        $data['title'] = 'Document Detail';
        return view('bulkupload::bulkUpload.documentDetail.index', $data);
    }
    public function uploadDocumentDetail(Request $request)
    {
        $files = $request->upload_document_detail;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $spreadsheet->getActiveSheet()->getStyle('E')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $spreadsheet->getActiveSheet()->getStyle('F')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        // dd($sheetData);
        array_shift($sheetData);

        $import_file = DocumentDetailImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Document Detail Imported Successfully');
        }

        return redirect()->route('bulkupload.documentDetail');
    }
    public function bankDetail()
    {
        $data['title'] = 'Bank Detail';
        return view('bulkupload::bulkUpload.bankDetail.index', $data);
    }
    public function uploadBankDetail(Request $request)
    {
        $files = $request->upload_bank_detail;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);

        $import_file = BankDetailImport::import($sheetData);
        if ($import_file['success']) {
            return redirect()->back()->with('success', $import_file['message']);
        } else {
            return redirect()->back()->with('error', $import_file['message']);
        }
        return redirect()->back();
    }

    public function atdLogDetail()
    {
        $data['title'] = 'Attendance Log Detail';
        return view('bulkupload::bulkUpload.attendanceLog.index', $data);
    }

    public function uploadAttendanceLog(Request $request)
    {
        try {
            Excel::import(new AttendanceLogDetailImport(), $request->file('upload_atd_log_detail'));
            return redirect()->back()->with('success', 'Attendance Log Imported Successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
        if ($import_file['success']) {
            return redirect()->back()->with('success', $import_file['message']);
        } else {
            return redirect()->back()->with('error', $import_file['message']);
        }

        toastr()->success('Attendance Log Imported Successfully');
        return redirect()->route('bulkupload.attendanceLog');
    }

    public function carrierMobility()
    {
        $data['title'] = 'Career Mobility';
        return view('bulkupload::bulkUpload.carrierMobility.index', $data);
    }
    public function uploadCarrierMobility(Request $request)
    {
        $files = $request->upload_carrier_mobility;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $spreadsheet->getActiveSheet()->getStyle('C')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);
        $import_file = CarrierMobilityImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Career Mobility Detail Imported Successfully');
        }

        return redirect()->route('bulkupload.carrierMobility');
    }

    public function empBiometricDetail()
    {
        $data['title'] = 'Employee Biomteric Detail';
        return view('bulkupload::bulkUpload.biometricDetail.index', $data);
    }

    public function uploadEmpBiometricDetail(Request $request)
    {
        Excel::import(new EmpBiometricImport(), $request->file('upload_biomteric_detail'));
        toastr()->success('Employee Biomteric Imported Successfully');
        return redirect()->route('bulkupload.empBiometricDetail');
    }

    public function employeeJobDescription(Request $request)
    {
        $data['title'] = 'Employee Job Description';
        return view('bulkupload::bulkUpload.employeeJobDescription.index', $data);
    }
    public function uploadEmployeeJobDescription(Request $request)
    {
        $files = $request->upload_employee_job_description;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);

        $import_file = EmployeeJobDescriptionImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Employee Job Description Imported Successfully');
        }

        return redirect()->route('bulkupload.employeeJobDescription');
    }


    public function darbandis(Request $request)
    {
        $data['title'] = 'Darbandi Upload';
        return view('bulkupload::bulkUpload.darbandi.index', $data);
    }

    public function uploadDarbandi(Request $request)
    {

        $files = $request->upload_darbandi;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);


        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);
        $import_file = ImportFile::import(new DarbandiImport, $sheetData);

        if ($import_file) {
            toastr()->success('Daarbandi Imported Successfully');
        }

        return back();
    }

    public function holiday(Request $request)
    {
        $data['title'] = 'Holiday Upload';
        return view('bulkupload::bulkUpload.holiday.index', $data);
    }

    public function uploadHoliday(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('upload_holiday')) {
            $file_info = $request->upload_holiday->getClientOriginalName();
            $extension = \File::extension($file_info);
            if ($extension == 'xlsx' || $extension == 'xlx' || $extension == 'xls') {
                $file_directory = public_path() . '/uploads/uploads_xls/';
                $new_file_name = date("d-m-Y ") . rand(000000, 999999) . "." . $extension;
                if (move_uploaded_file($_FILES['upload_holiday']['tmp_name'], $file_directory . $new_file_name)) {
                    $inputFileName = $file_directory . $new_file_name;
                    $file_type = IOFactory::identify($inputFileName);
                    $objReader = IOFactory::createReader($file_type);

                    $objPHPExcel = $objReader->load($file_directory . $new_file_name);
                    $grn_data  = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                    array_shift($grn_data);
                    $import_file = ImportFile::import(new HolidayImport, $grn_data);
                }
            }
        }

        // $files = $request->upload_darbandi;
        // $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        // $reader->setReadDataOnly(true);

        // $spreadsheet = $reader->load($files);


        // $sheetData = $spreadsheet->getActiveSheet()->toArray();
        // array_shift($sheetData);
        // $import_file = ImportFile::import(new HolidayImport, $sheetData);

        if ($import_file) {
            toastr()->success('Holiday Imported Successfully');
        }

        return back();
    }

    public function user(Request $request)
    {
        $data['title'] = 'User Upload';
        return view('bulkupload::bulkUpload.user.index', $data);
    }

    public function exportUser()
    {
        $filter = [];
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $employees = (new EmployeeRepository)->findAll(null, $filter, $sort);
        $users = [];
        if (!empty($employees)) {
            foreach ($employees as $employee) {
                if (isset($employee->getUser)) {
                    $username = optional($employee->getUser)->username;
                } else {
                    //check duplicate username
                    $username = $employee['first_name'] . '.' . $employee['last_name'];
                    $check = (new UserRepository)->checkUsername($username);
                    if (count($check) > 0) {
                        $username = $employee['last_name'] . '.' . $employee['first_name'];
                        $check1 = (new UserRepository)->checkUsername($username);
                        if (count($check1) > 0) {
                            $username = $employee['first_name'] . '.' . $employee['last_name'] . rand(1000, 9999);
                        }
                    }
                    //
                }
                $users['employee_code'] = $employee['employee_code'];
                $users['first_name'] = $employee['first_name'];
                $users['middle_name'] = $employee['middle_name'];
                $users['last_name'] = $employee['last_name'];
                $users['username'] = $username;
                // $users['password'] = 'Password@123';
                $users['role'] = 'employee';
                $data['users'][] = $users;
            }
        }
        return Excel::download(new UserDetailExport($data), 'userDetail.xlsx');
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();
    }

    public function uploadUser(Request $request)
    {
        if ($request->hasFile('upload_user')) {
            Excel::import(new UsersImport, $request->file('upload_user'));
            toastr()->success('User Imported Successfully');
        } else {
            toastr()->error('Please Select File');
        }
        return redirect()->back();
    }

    public function employeeDetail()
    {
        $data['title'] = 'Detail';
        return view('bulkupload::bulkUpload.employeeDetail.index', $data);
    }

    public function addDropdownToColumnAB()
    {
        $filePath = public_path('samples/Employee_Detail_Sample.xlsx');
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $dropdownSheet = $spreadsheet->createSheet();
        $dropdownSheet->setTitle('DropdownValues');
        $dropdownSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
        $departments = Department::pluck('title')->toArray();
        $row = 1;
        foreach ($departments as $dept) {
            $dropdownSheet->setCellValue("A$row", $dept);
            $row++;
        }
        $spreadsheet->addNamedRange(
            new \PhpOffice\PhpSpreadsheet\NamedRange(
                'DepartmentList',
                $dropdownSheet,
                '$A$1:$A$' . (count($departments))
            )
        );
        for ($row = 2; $row <= 1000; $row++) {
            $cell = "AB$row";
            $validation = $sheet->getCell($cell)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('=DepartmentList');
        }
        $newPath = public_path('samples/Employee_Detail_Sample_Updated.xlsx');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($newPath);
        return response()->download($newPath)->deleteFileAfterSend(true);
    }

    public function addDropdownToEmployeeSample()
    {
        $filePath = public_path('downloads/employee_sample/sample_employee.xlsx');
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();


        $dropdownSheet = $spreadsheet->createSheet();
        $dropdownSheet->setTitle('DropdownValues');
        $dropdownSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

        $departments = Department::pluck('title')->toArray();
        $organizations = Organization::pluck('name')->toArray();
        $branches = Branch::pluck('name')->toArray();
        $designations = Designation::pluck('title')->toArray();
        $levels = Level::pluck('title')->toArray();

        $row = 1;
        foreach ($departments as $dept) {
            $dropdownSheet->setCellValue("A$row", $dept);
            $row++;
        }

        $branchStartRow = $row;
        foreach ($branches as $branch) {
            $dropdownSheet->setCellValue("A$row", $branch);
            $row++;
        }
        $branchEndRow = $row - 1;

        $orgStartRow = $row;
        foreach ($organizations as $org) {
            $dropdownSheet->setCellValue("A$row", $org);
            $row++;
        }
        $orgEndRow = $row - 1;

        $degStartRow = $row;
        foreach ($designations as $deg) {
            $dropdownSheet->setCellValue("A$row", $deg);
            $row++;
        }
        $degEndRow = $row - 1;
        $levelStartRow = $row;
        foreach ($levels as $level) {
            $dropdownSheet->setCellValue("A$row", $level);
            $row++;
        }
        $levelEndRow = $row - 1;

        $spreadsheet->addNamedRange(
            new \PhpOffice\PhpSpreadsheet\NamedRange(
                'DepartmentList',
                $dropdownSheet,
                '$A$1:$A$' . count($departments)
            )
        );
        $spreadsheet->addNamedRange(
            new \PhpOffice\PhpSpreadsheet\NamedRange(
                'orgList',
                $dropdownSheet,
                '$A$' . $orgStartRow . ':$A$' . $orgEndRow
            )
        );
        $spreadsheet->addNamedRange(
            new \PhpOffice\PhpSpreadsheet\NamedRange(
                'BranchList',
                $dropdownSheet,
                '$A$' . $branchStartRow . ':$A$' . $branchEndRow
            )
        );
        $spreadsheet->addNamedRange(
            new \PhpOffice\PhpSpreadsheet\NamedRange(
                'DesignationList',
                $dropdownSheet,
                '$A$' . $degStartRow . ':$A$' . $degEndRow
            )
        );
        $spreadsheet->addNamedRange(
            new \PhpOffice\PhpSpreadsheet\NamedRange(
                'LevelList',
                $dropdownSheet,
                '$A$' . $levelStartRow . ':$A$' . $levelEndRow
            )
        );


        for ($row = 2; $row <= 1000; $row++) {
            $deptCell = "S$row";
            $deptValidation = $sheet->getCell($deptCell)->getDataValidation();
            $deptValidation->setType(DataValidation::TYPE_LIST);
            $deptValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $deptValidation->setAllowBlank(true);
            $deptValidation->setShowInputMessage(true);
            $deptValidation->setShowErrorMessage(true);
            $deptValidation->setShowDropDown(true);
            $deptValidation->setFormula1('=DepartmentList');

            $branchCell = "R$row";
            $branchValidation = $sheet->getCell($branchCell)->getDataValidation();
            $branchValidation->setType(DataValidation::TYPE_LIST);
            $branchValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $branchValidation->setAllowBlank(true);
            $branchValidation->setShowInputMessage(true);
            $branchValidation->setShowErrorMessage(true);
            $branchValidation->setShowDropDown(true);
            $branchValidation->setFormula1('=BranchList');

            $orgCell = "P$row";
            $orgValidation = $sheet->getCell($orgCell)->getDataValidation();
            $orgValidation->setType(DataValidation::TYPE_LIST);
            $orgValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $orgValidation->setAllowBlank(true);
            $orgValidation->setShowInputMessage(true);
            $orgValidation->setShowErrorMessage(true);
            $orgValidation->setShowDropDown(true);
            $orgValidation->setFormula1('=orgList');

            $degCell = "U$row";
            $degValidation = $sheet->getCell($degCell)->getDataValidation();
            $degValidation->setType(DataValidation::TYPE_LIST);
            $degValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $degValidation->setAllowBlank(true);
            $degValidation->setShowInputMessage(true);
            $degValidation->setShowErrorMessage(true);
            $degValidation->setShowDropDown(true);
            $degValidation->setFormula1('=DesignationList');

            $levelCell = "T$row";
            $levelValidation = $sheet->getCell($levelCell)->getDataValidation();
            $levelValidation->setType(DataValidation::TYPE_LIST);
            $levelValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $levelValidation->setAllowBlank(true);
            $levelValidation->setShowInputMessage(true);
            $levelValidation->setShowErrorMessage(true);
            $levelValidation->setShowDropDown(true);
            $levelValidation->setFormula1('=LevelList');
        }

        $newPath = public_path('samples/Employee_Detail_Sample_Updated.xlsx');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($newPath);

        return response()->download($newPath)->deleteFileAfterSend(true);
    }




    public function uploadEmployeeDetail(Request $request)
    {
        $files = $request->upload_employee_detail;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $spreadsheet->getActiveSheet()->getStyle('G')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $spreadsheet->getActiveSheet()->getStyle('H')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $spreadsheet->getActiveSheet()->getStyle('S')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);

        $import_file = EmployeeDetailImport::import($sheetData);
        if ($import_file['success']) {
            return redirect()->back()->with('success', $import_file['message']);
        } else {
            return redirect()->back()->with('error', $import_file['message']);
        }

        return redirect()->route('bulkupload.employeeDetail');
    }


    public function labour(Request $request)
    {
        $data['title'] = 'Labour Upload';
        return view('bulkupload::bulkUpload.labour.index', $data);
    }


    public function uploadLabour(Request $request)
    {
        $files = $request->upload_labour;
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($files);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

        $spreadsheet->getActiveSheet()->getStyle('E')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        array_shift($sheetData);

        $import_file = LabourImport::import($sheetData);

        if ($import_file) {
            toastr()->success('Labour Imported Successfully');
        }

        return redirect()->route('bulkupload.labour');
    }

    public function attendanceOverStay(Request $request)
    {
        $data['title'] = 'Attendace Over Stay';
        return view('bulkupload::bulkUpload.attendance-overstay.index', $data);
    }

    public function uploadAttendanceOverStay(Request $request)
    {
        $request->validate([
            'upload_atd_over_stay' => 'required|file|mimes:xlsx'
        ]);

        try {
            $file = $request->file('upload_atd_over_stay');
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
            $reader->setReadDataOnly(true);

            $spreadsheet = $reader->load($file->getPathname());
            \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(
                new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder()
            );

            // Format date column properly
            $spreadsheet->getActiveSheet()->getStyle('B')
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            // Remove header row and keep only first 3 columns
            array_shift($sheetData);
            $sheetData = array_map(function ($row) {
                return [
                    'employee_code' => $row[0] ?? null,
                    'date' => $row[1] ?? null,
                    'actual_ot' => $row[2] ?? null
                ];
            }, $sheetData);

            $successCount = 0;
            $errorMessages = [];
            $processedRows = 0;

            DB::beginTransaction();

            foreach ($sheetData as $index => $row) {
                $processedRows++;
                $rowNumber = $index + 2; // Account for header row + 1-based index

                try {
                    // Validate required fields
                    if (empty($row['employee_code']) || empty($row['date']) || empty($row['actual_ot'])) {
                        $errorMessages[] = "Row {$rowNumber}: Missing required data";
                        continue;
                    }

                    // Find employee
                    $emp = Employee::where('employee_code', trim($row['employee_code']))->first();
                    if (!$emp) {
                        throw new Exception("Employee not found: {$row['employee_code']}");
                    }

                    // Parse and format date
                    try {
                        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date'])->format('Y-m-d');
                    } catch (\Exception $e) {
                        $date = Carbon::parse($row['date'])->format('Y-m-d');
                    }

                    // Find or create attendance record
                    $att = Attendance::firstOrCreate(
                        [
                            'emp_id' => $emp->id,
                            'date' => $date
                        ],
                        [
                            'actual_ot' => 0 // Default value if creating new
                        ]
                    );

                    // Update overtime
                    $att->update(['actual_ot' => $row['actual_ot']]);
                    $successCount++;
                } catch (Exception $e) {
                    $errorMessages[] = "Row {$rowNumber}: " . $e->getMessage();
                    Log::error("OT Import Error - Row {$rowNumber}: " . $e->getMessage());
                }
            }

            DB::commit();

            // Prepare result message
            $message = "Processed {$processedRows} rows. ";
            $message .= "Successfully updated {$successCount} records.";

            if (!empty($errorMessages)) {
                $message .= " Encountered " . count($errorMessages) . " errors.";
                toastr()->warning($message);
                session()->flash('import_errors', $errorMessages);
            } else {
                toastr()->success($message);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error("Failed to process file: " . $e->getMessage());
            Log::error("Attendance OT Import Failed: " . $e->getMessage());
        }

        return back();
    }
}
