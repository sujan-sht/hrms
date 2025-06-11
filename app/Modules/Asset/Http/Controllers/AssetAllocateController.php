<?php

namespace App\Modules\Asset\Http\Controllers;

use App\Exports\AssetAllocationReport;
use App\Modules\Asset\Entities\AssetAllocateAttachment;
use App\Modules\Asset\Repositories\AssetAllocateInterface;
use App\Modules\Asset\Repositories\AssetInterface;
use App\Modules\Asset\Repositories\AssetQuantityInterface;
use App\Modules\Employee\Repositories\EmployeeInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;

class AssetAllocateController extends Controller
{
    private $assetAllocate;
    private $asset;
    private $employee;
    private $assetQuantity;

    public function __construct(
        AssetAllocateInterface $assetAllocate,
        AssetInterface $asset,
        EmployeeInterface $employee,
        AssetQuantityInterface $assetQuantity
    ) {
        $this->assetAllocate = $assetAllocate;
        $this->asset = $asset;
        $this->employee = $employee;
        $this->assetQuantity = $assetQuantity;
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
            'sort' => 'desc'
        ];
        $data['assetAllocateModels'] = $this->assetAllocate->findAll(10, $filter, $sort);
        $data['assets'] = $this->asset->getList();
        $data['employees'] = $this->employee->getList();
        return view('asset::asset-allocate.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['assets'] = $this->asset->getList();
        $data['employees'] = $this->employee->getList();
        $data['isEdit'] = false;
        return view('asset::asset-allocate.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = $request->except('_token');

        try {
            $inputData['return_date'] = ($inputData['return_date'] && setting('calendar_type') == "BS") ? date_converter()->nep_to_eng_convert($inputData['return_date']) : $inputData['return_date'];
            $inputData['allocated_date'] = ($inputData['allocated_date'] && setting('calendar_type') == "BS") ? date_converter()->nep_to_eng_convert($inputData['allocated_date']) : $inputData['allocated_date'];

            if (!$inputData['allocated_date']) {
                $inputData['allocated_date'] = date('Y-m-d');
            }
            $inputData['allocated_by'] = Auth::user()->id;

            $assetModel = $this->assetAllocate->save($inputData);
            if ($assetModel) {
                $this->assetQuantity->updateRemainingQuantity($inputData, 'Sub');
                if ($request->has('attachments')) {
                    foreach ($inputData['attachments'] as $attachment) {
                        $this->uploadAttachment($assetModel->id, $attachment);
                    }
                }
            }
            $this->assetAllocate->sendMailNotification($assetModel);
            toastr()->success('Asset Allocate Added Successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->route('assetAllocate.index');
    }
    
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('asset::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['assetAllocateModel'] = $this->assetAllocate->find($id);
        $data['assets'] = $this->asset->getList();
        $data['employees'] = $this->employee->getList();
        $data['isEdit'] = true;
        return view('asset::asset-allocate.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $assetAllocateOld = $this->assetAllocate->find($id);
        $inputData = $request->except('_token');
        try {
            // $netQuantity = $inputData['quantity'] - $assetAllocateOld['quantity'];    //gives either +ve or -ve value
            // if ($netQuantity > 0 || $netQuantity < 0) {
            //     $inputData['quantity'] = $assetAllocateOld['quantity'] + $netQuantity;
            // }

            $inputData['return_date'] = ($inputData['return_date'] && setting('calendar_type') == "BS") ? date_converter()->nep_to_eng_convert($inputData['return_date']) : $inputData['return_date'];
            $inputData['allocated_date'] = ($inputData['allocated_date'] && setting('calendar_type') == "BS") ? date_converter()->nep_to_eng_convert($inputData['allocated_date']) : $inputData['allocated_date'];
            if (!$inputData['allocated_date']) {
                $inputData['allocated_date'] = date('Y-m-d');
            }
            $allocate = $this->assetAllocate->update($id, $inputData);
            if ($allocate) {
                $assetQuantity = $this->assetQuantity->checkAssetExits($assetAllocateOld['asset_id']);

                // if ($netQuantity > 0 || $netQuantity < 0) {
                //     $data['remaining_quantity'] = $assetQuantity['remaining_quantity'] - $netQuantity;
                // }
                // $this->assetQuantity->update($assetQuantity['id'], $data);

                if ($request->has('attachments')) {
                    $assetAllocateOld->assetAllocateAttachment()->delete();

                    foreach ($inputData['attachments'] as $attachment) {
                        $this->uploadAttachment($id, $attachment);
                    }
                }
            }
            toastr()->success('Asset Allocate Updated Successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->route('assetAllocate.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $assetAllocate = $this->assetAllocate->find($id);
            $isDelete = $this->assetAllocate->delete($id);
            if ($isDelete) {
                $data['asset_id'] = $assetAllocate['asset_id'];
                $data['quantity'] = $assetAllocate['quantity'];
                $this->assetQuantity->updateRemainingQuantity($data, 'Add');
            }

            toastr()->success('Asset Allocate Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    public function export(Request $request) 
    {
        $filter = $request->all();
        $sort = [
            'by' => 'id',
            'sort' => 'DESC'
        ];
        $data['allocations'] = $this->assetAllocate->findAll(Config::get('allocation.export-length'), $filter, $sort);
        return Excel::download(new AssetAllocationReport($data), 'asset-allocation-report.xlsx');
        toastr('Please Filter first to download Excel Report', 'warning');
        return back();
    }

    public function uploadAttachment($id, $file)
    {
        $fileDetail = AssetAllocateAttachment::saveFile($file);
        $modelData['asset_allocate_id'] = $id;
        $modelData['title'] = $fileDetail['filename'];
        $modelData['extension'] = $fileDetail['extension'];
        $modelData['size'] = $fileDetail['size'];

        AssetAllocateAttachment::create($modelData);
    }
}
