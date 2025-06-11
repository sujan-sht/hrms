<?php

namespace App\Modules\PMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

use App\Modules\PMS\Repositories\KPIInterface;
use App\Modules\PMS\Repositories\KRAInterface;
use App\Modules\PMS\Http\Requests\CreateKpiRequest;

class KPIController extends Controller
{
    protected $kpi;
    protected $kra;

    public function __construct(KPIInterface $kpi, KRAInterface $kra)
    {
        $this->kpi = $kpi;
        $this->kra = $kra;
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
        $data['kpiModels'] = $this->kpi->findAll(20, $filter, $sort);
        $data['kraList'] = $this->kra->getList();

        return view('pms::kpi.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['kraList'] = $this->kra->getList();
        return view('pms::kpi.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateKpiRequest $request)
    {
        $inputData = $request->all();
        $inputData['date'] = date('Y-m-d');
        $inputData['created_by'] = Auth::user()->id;
        try {
            $this->kpi->create($inputData);
            toastr()->success('KPI Created Successfully');
        } catch (\Throwable $e) {
            throw $e;
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect(route('kpi.index'));
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
        $data['kpiModel'] = $this->kpi->findOne($id);
        $data['kraList'] = $this->kra->getList();

        return view('pms::kpi.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CreateKpiRequest $request, $id)
    {
        $data = $request->all();
        $data['updated_by'] = Auth::user()->id;
        try {
            $this->kpi->update($id, $data);

            toastr()->success('KPI Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('kpi.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->kpi->delete($id);
            toastr()->success('KPI Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }
        return redirect()->back();
    }
}
