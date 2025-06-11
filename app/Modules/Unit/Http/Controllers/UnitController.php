<?php

namespace App\Modules\Unit\Http\Controllers;

use App\Exports\UnitExport;
use Illuminate\Http\Request;
use App\Service\Import\ImportFile;
use App\Service\Import\UnitImport;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\Unit\Entities\Unit;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    private $organization;
    private $branch;
    private $unit;

    public function __construct(OrganizationInterface $organization, BranchInterface $branch, Unit $unit)
    {
        $this->organization=$organization;
        $this->branch = $branch;
        $this->unit = $unit;
    }
    public function index(Request $request)
    {
        $filter = $request->all();
        $data['unitModels'] = $this->unit->paginate(10);
        $data['branches'] = $this->branch->getList();
        return view('unit::unit.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['organizations'] = $this->organization->getList();
        $data['branches'] = $this->branch->branchesData();
        $data['statuses'] = [
            '1' => 'Active',
            '0' => 'In-Active'
        ];
        return view('unit::unit.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            // dd($data);
            if (!$data['status']) {
                $data['status'] = '0';
            }
            $this->unit->fill($data);
            $this->unit->save();
            DB::commit();
            toastr()->success('Unit created successfully !!!');
            return redirect()->route('unit.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error('Something Went Wrong !!!');
            return redirect()->back();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('unit::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($unit)
    {
        $data['organizations'] = $this->organization->getList();
        $data['unit'] = $this->unit->find($unit);
        $data['branches'] = $this->branch->branchesData();
        $data['statuses'] = [
            '1' => 'Active',
            '0' => 'In-Active'
        ];
        return view('unit::unit.create', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $unit)
    {
        $this->unit = $this->unit->find($unit);
        DB::beginTransaction();
        try {
            $data = $request->all();
            if (!$data['status']) {
                $data['status'] = '0';
            }
            $this->unit->fill($data);
            $this->unit->save();
            DB::commit();
            toastr()->success('Unit updated successfully !!!');
            return redirect()->route('unit.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error('Something Went Wrong !!!');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $this->unit = $this->unit->find($id);
        DB::beginTransaction();
        try {
            $this->unit->delete();
            DB::commit();
            toastr()->success('Unit deleted successfully !!!');
            return redirect()->route('unit.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toastr()->error('Something Went Wrong !!!');
            return redirect()->back();
        }
    }
    public function export(Request $request)
    {
        
        $filter = $request->all();

        $data['unitModels'] = $this->unit->get();

        return Excel::download(new UnitExport($data),'unit-report.xlsx');
    }

    public function uploadEmployee(Request $request)
    {
        $files = $request->upload_unit;
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

        $spreadsheet->getActiveSheet()->getStyle('X')
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        array_shift($sheetData);
        $import_file = ImportFile::import(new UnitImport, $sheetData);

        if ($import_file) {
            toastr()->success('Unit Imported Successfully');
        }

        return redirect()->route('unit.index');
    }

}
