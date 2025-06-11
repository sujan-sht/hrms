<?php

namespace App\Modules\FuelConsumption\Http\Controllers;

use App\Exports\FuelConsumptionReport;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\FuelConsumption\Repositories\FuelConsumptionInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
// For excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Traits\LogTrait;
class FuelConsumptionController extends Controller
{
    use LogTrait;
    protected $fuelConsumption;
    protected $employee;
    protected $organization;


    public function __construct(FuelConsumptionInterface $fuelConsumption, EmployeeInterface $employee, OrganizationInterface $organization)
    {
        $this->fuelConsumption = $fuelConsumption;
        $this->employee = $employee;
        $this->organization = $organization;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $search = $request->all();
        if (isset($search['from_date']) && isset($search['to_date'])) {

            $search['search_from_to'] = date_converter()->nep_to_eng_convert($search['from_date']) . ' - ' . date_converter()->nep_to_eng_convert($search['to_date']);
        }
        $user = Auth::user();

        if (strtolower($user->user_type) == 'employee') {
            $data['fuelconsumptions'] = $this->fuelConsumption->findAllByEmployee($user->emp_id, $limit = 10, $search);
        } else {
            $data['fuelconsumptions'] = $this->fuelConsumption->findAll($limit = 10, $search);
        }
        $data['total_km_travelled'] = $data['fuelconsumptions']->sum('km_travelled');
        $data['total_parking_cost'] = $data['fuelconsumptions']->sum('parking_cost');
        $data['organizationList'] = $this->organization->getList();
        if (isset($search['organizationId'])) {
            $data['employee'] = Employee::whereIn('organization_id', $search['organizationId'])->get()->pluck('full_name', 'id');
        } else {

            $data['employee'] = $this->employee->getList();
        }
        $data['search_value'] = $search;

        return view('fuelconsumption::fuelConsumption.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['employee'] = $this->employee->getList();
        return view('fuelconsumption::fuelConsumption.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data['fuel_consump_created_date'] = date('Y-m-d');
            $data['status'] = 'pending';
            $data['verified_status'] = 'No';
            $data['created_by'] = Auth::user()->id;

            $this->fuelConsumption->save($data);
            toastr()->success('Fuel Consumption Created Successfully !!!');
        } catch (\Throwable $e) {

            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('fuelConsumption'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        // return view('fuelConsumption::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request)
    {
        $data['is_edit'] = true;
        $input = $request->all();
        $id = $input['fuelconsumption_id'];
        $data['fuelConsumption'] = $this->fuelConsumption->find($id);
        $data['employee'] = $this->employee->getList();
        return view('fuelconsumption::fuelConsumption.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        try {
            $update_data = [
                'starting_place' => $data['starting_place'],
                'destination_place' => $data['destination_place'],
                'vehicle_no' => $data['vehicle_no'],
                'start_km' => $data['start_km'],
                'end_km' => $data['end_km'],
                'km_travelled' => $data['km_travelled'],
                'purpose' => $data['purpose'],
                'parking_cost' => $data['parking_cost'],
                'emp_id' => $data['emp_id'],
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id
                // 'status' => $data['status'],
            ];

            $this->fuelConsumption->update($id, $update_data);
            toastr()->success('Fuel Consumption updated successfully !!!');
        } catch (\Throwable $e) {

            toastr()->error('Something went wrong !!!');
        }
        return redirect(route('fuelConsumption'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->fuelConsumption->delete($id);
            toastr()->success('Fuel Consumption Deleted Successfully !!!');
        } catch (\Throwable $e) {

            toastr()->error('Something went wrong');
        }
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();
        $id = $input['fuelconsump_id'];
        $status = $input['status'];

        if ($status != '') {
            $updateData = array(
                'status' => $input['status'],
                'approved_by' => $user->id,
                'approved_at' => Date('Y-m-d H:i:s'),
            );
            $this->fuelConsumption->update($id, $updateData);
        }
        toastr()->success('Fuel Consumption Status Updated Successfully');
        return redirect()->back();
    }

    public function verifyRequest($id)
    {
        $user = Auth::user();
        $updateData = array(
            'verified_status' => 'Yes',
            'verified_by' => $user->id,
            'verified_at' => Date('Y-m-d H:i:s')
        );
        $this->fuelConsumption->update($id, $updateData);
        toastr()->success('Fuel Consumption Verified Successfully');
        return redirect()->back();
    }

    public function getfuelConsumptionDetailAjax(Request $request)
    {
        if ($request->ajax()) {
            $fuelconsumption_id = $request->fuelconsumption_id;
            $fuelConsumptionDetail = $this->fuelConsumption->find($fuelconsumption_id);
            // $setting=Setting::find(1);
            $data = view('fuelconsumption::fuelConsumption.partial.fuelconsumption-detail-ajax', compact('fuelConsumptionDetail'))->render();
            return response()->json(['options' => $data]);
        }
    }

    public function printInvoice($id)
    {
        $data['fuelconsumptionDetail'] = $this->fuelConsumption->find($id);
        // $data['setting']=Setting::find(1);
        return view('fuelconsumption::fuelConsumption.partial.print-invoice', $data);
    }

    public function FuelConsumptionDownload(Request $request)
    {
        $search = $request->all();
        $user = Auth::user();
        // dd( $inputData);
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $year = date('Y');
        $objPHPExcel = new Spreadsheet();
        $worksheet = $objPHPExcel->getActiveSheet();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Created Date');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Employee Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Starting Place');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Destination Place');
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Vehicle No.');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Start Km');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'End Km');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Km Travelled');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Purpose');
        $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Parking Cost');
        // $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Status');

        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);


        if (strtolower($user->user_type) == 'employee') {
            $fuelConsumption = $this->fuelConsumption->findAllByEmployee($user->emp_id, $limit = 10, $search);
        } else {
            $fuelConsumption = $this->fuelConsumption->findAll($limit = 10, $search);
        }
        // $fuelConsumption = $this->fuelConsumption->findAll($);
        // dd($fuelConsumption);
        $num = 2;
        foreach ($fuelConsumption as $value) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $num, $value->fuel_consump_created_date);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $num, optional($value->employeeInfo)->first_name . ' ' . optional($value->employeeInfo)->last_name);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $num, $value->starting_place);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $num, $value->destination_place);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $num, $value->vehicle_no);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $num, $value->start_km);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $num, $value->end_km);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $num, $value->km_travelled);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $num, $value->purpose);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $num, $value->parking_cost);
            // $objPHPExcel->getActiveSheet()->SetCellValue('K'.$num, $value->status);
            $num++;
        }

        $file = 'fuelConsumption' . $year;
        $filename = $file . '.xlsx';
        $writer = new Xlsx($objPHPExcel);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($filename) . '"');
        $writer->save('php://output');
    }

    // public function fuelConsumptionReportExport(Request $request)
    // {
    //     $inputData = $request->all();
    //     // dd($inputData);

    //     $data['column_lists'] = [
    //         'fuel_consump_created_date' => 'Created Date', 'mobile' => 'Employee Name', 'starting_place' => 'Starting Place',
    //         'destination_place' => 'Destination Place', 'vehicle_no' => 'Vehicle No.', 'start_km' => 'Start Km', 'end_km' => 'End Km',
    //         'km_travelled' => 'Km Travelled', 'purpose' => 'Purpose','parking_cost' => 'Parking Cost'
    //     ];
    //     dd($data['column_lists']);

    //     $data['displayAll'] = isset($request->columns) && count($request->columns) > 0 ? true : false;
    //     $data['select_columns'] = $request->columns;

    //     $limit = null;
    //     // if (isset($inputData['sortBy']) && !empty($inputData['sortBy'])) {
    //     //     $limit = $inputData['sortBy'];
    //     // }

    //     $inputData['archive_status'] = 10;
    //     $data['employeeModels'] = $this->fuelConsumption->findAll($limit, $inputData);
    //     $data['title'] = 'Employee';


    //     return Excel::download(new FuelConsumptionReport($data), 'employee-directory-report.xlsx');
    //     toastr('Please Filter first to download Excel Report', 'warning');
    //     return back();
    // }
}
