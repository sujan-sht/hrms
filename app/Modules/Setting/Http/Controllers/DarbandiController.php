<?php

namespace App\Modules\Setting\Http\Controllers;

use App\Service\Import\DarbandiImport;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Employee\Repositories\EmployeeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Entities\Darbandi;
use App\Modules\Setting\Http\Requests\DarbandiRequest;
use App\Modules\Setting\Repositories\DarbandiInterface;
use App\Modules\Setting\Repositories\DesignationInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Service\Import\ImportFile;

class DarbandiController extends Controller
{
    protected $organization;
    protected $employee;
    protected $dropdown;
    protected $darbandi;
    protected $designation;

    public function __construct(
        OrganizationInterface $organization,
        EmployeeInterface $employee,
        DropdownInterface $dropdown,
        DarbandiInterface $darbandi,
        DesignationInterface $designation
    ) {
        $this->organization = $organization;
        $this->employee = $employee;
        $this->dropdown = $dropdown;
        $this->darbandi = $darbandi;
        $this->designation = $designation;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $data['darbandis'] = $this->darbandi->findAll();
        return view('setting::darbandi.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['organizations'] = $this->organization->findAll()->pluck('name', 'id');
        $data['designationList'] = $this->designation->getList();

        return view('setting::darbandi.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(DarbandiRequest $request)
    {
        $data = $request->all();

        try {
            $this->darbandi->save($data);
            toastr()->success('Darbandi Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('darbandi.index'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('setting::darbandi.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['organizations'] = $this->organization->findAll()->pluck('name', 'id');
        $data['designationList'] = $this->designation->getList();
        $data['darbandi'] = $this->darbandi->find($id);
        return view('setting::darbandi.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(DarbandiRequest $request, $id)
    {
        $data = $request->all();

        try {
            $this->darbandi->update($id, $data);
            toastr()->success('Darbandi Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('darbandi.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->darbandi->delete($id);

            toastr()->success('Darbandi Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('darbandi.index'));
    }

    // public function uploadDarbandi(Request $request)
    // {
    //     Excel::import(new DarbandiImport, $request->upload_darbandi);
    // }

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


    // public function getEmployee(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $filterData = [
    //             'organization_id' => $request->organization_id,
    //             'designation_id' => $request->designation_id
    //         ];
    //         $employeeList = Employee::getOrganizationwiseEmployees($filterData);

    //         // dd($employeeList);

    //         $employeedata = view('setting::darbandi.partial.employee-option', compact('employeeList'))->render();

    //         return response()->json(['employeedata' => $employeedata]);
    //     }
    // }
}
