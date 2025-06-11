<?php

namespace App\Modules\PMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\PMS\Repositories\KRAInterface;
use App\Modules\PMS\Http\Requests\CreateKraRequest;
use App\Modules\Setting\Repositories\DepartmentInterface;

class KRAController extends Controller
{
    protected $dropdown;
    protected $kra;
    private $organizationObj;
    private $department;

    public function __construct(DropdownInterface $dropdown, KRAInterface $kra, OrganizationInterface $organizationObj, DepartmentInterface $department)
    {
        $this->dropdown = $dropdown;
        $this->kra = $kra;
        $this->organizationObj = $organizationObj;
        $this->department = $department;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['kraModels'] = $this->kra->findAll(20, $filter, $sort);
        $data['departmentList'] = $this->department->getList();
        $data['organizationList'] = $this->organizationObj->getList();

        return view('pms::kra.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['departmentList'] = $this->department->getList();
        $data['organizationList'] = $this->organizationObj->getList();

        return view('pms::kra.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateKraRequest $request)
    {
        $inputData = $request->all();
        $inputData['date'] = date('Y-m-d');
        $inputData['created_by'] = Auth::user()->id;
        try {
            $this->kra->create($inputData);
            toastr()->success('KRA Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('kra.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('pms::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['departmentList'] = $this->department->getList();
        $data['organizationList'] = $this->organizationObj->getList();
        $data['kraModel'] = $this->kra->findOne($id);

        return view('pms::kra.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CreateKraRequest $request, $id)
    {
        $data = $request->all();
        $data['updated_by'] = Auth::user()->id;

        try {
            $this->kra->update($id, $data);

            toastr()->success('KRA Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('kra.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->kra->delete($id);

            toastr()->success('KRA Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }

    public function downloadSheet()
    {

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


        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Title');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Department');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Organization');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Created Date');


        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleArray);

        $kra_info = $this->kra->findAll();
        $num = 2;

        foreach ($kra_info as $key => $value) {
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $num, $value->title);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $num, optional($value->department)->title);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $num, optional($value->organization)->name);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $num, $value->date);
            $num++;
        }

        $writer = new Xlsx($objPHPExcel);
        $file = 'kra_' . $year;
        $filename = $file . '.xlsx';
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $writer->save('php://output');
    }
}
