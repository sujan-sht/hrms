<?php

namespace App\Modules\Asset\Http\Controllers;

use App\Modules\Asset\Repositories\AssetAllocateInterface;
use App\Modules\Asset\Repositories\AssetInterface;
use App\Modules\Asset\Repositories\AssetQuantityInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AssetQuantityController extends Controller
{
    private $assetQuantity;
    private $asset;

    public function __construct(
        AssetQuantityInterface $assetQuantity,
        AssetInterface $asset

    ) {
        $this->assetQuantity = $assetQuantity;
        $this->asset = $asset;
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
        $data['assetQuantityModels'] = $this->assetQuantity->findAll(10, $filter, $sort);
        $data['assets'] = $this->asset->getList();
        return view('asset::asset-quantity.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data['assets'] = $this->asset->getList();
        $data['isEdit'] = false;
        return view('asset::asset-quantity.create', $data);
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
            $inputData['expiry_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($inputData['expiry_date']) : $inputData['expiry_date'];
            $inputData['remaining_quantity'] = $inputData['quantity'];
            $inputData['created_by'] = Auth::user()->id;

            $this->assetQuantity->save($inputData);
            toastr()->success('Asset Quantity Added Successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->route('assetQuantity.index');
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
        $data['assetQuantityModel'] = $this->assetQuantity->find($id);
        $data['assets'] = $this->asset->getList();
        $data['isEdit'] = true;
        return view('asset::asset-quantity.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $assetQuantityOld = $this->assetQuantity->find($id);
        $inputData = $request->except('_token');

        try {
            $netQuantity = $inputData['quantity'] - $assetQuantityOld['quantity'];    //gives either +ve or -ve value
            if ($netQuantity > 0 || $netQuantity < 0) {
                $inputData['quantity'] = $assetQuantityOld['quantity'] + $netQuantity;
                $inputData['remaining_quantity'] = $assetQuantityOld['remaining_quantity'] + $netQuantity;
            }
            $inputData['expiry_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($inputData['expiry_date']) : $inputData['expiry_date'];
            $this->assetQuantity->update($id, $inputData);
            toastr()->success('Asset Quantity Updated Successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->route('assetQuantity.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $assetQuantity = $this->assetQuantity->find($id);
            $isDelete = $this->assetQuantity->delete($id);
            if ($isDelete) {
                $this->asset->deleteAssetAllocate($assetQuantity['asset_id']);
            }
            toastr()->success('Asset Quantity Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }

    public function checkAssetExists(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->assetQuantity->checkAssetExits($request->asset_id);
            return json_encode($data);
        }
    }
}
