<?php

namespace App\Modules\Asset\Http\Controllers;

use App\Modules\Asset\Repositories\AssetInterface;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AssetController extends Controller
{
    private $asset;

    public function __construct(
        AssetInterface $asset
    ) {
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
        $data['assetModels'] = $this->asset->findAll(10, $filter, $sort);
        return view('asset::asset.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('asset::asset.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $inputData = $request->except('_token');
        $assets = explode(',', $inputData['title']);
        try {
            foreach ($assets as $asset) {
                $data['title'] = trim($asset);
                // $data['created_by'] = auth()->user()->id;
                $this->asset->save($data);
            }
            toastr()->success('Asset Added Successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->route('asset.index');
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
        $data['assetModel'] = $this->asset->find($id);
        return view('asset::asset.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $inputData = $request->all();
        try {
            $this->asset->update($id, $inputData);
            toastr()->success('Asset Updated Successfully !!!');
        } catch (\Throwable $th) {
            toastr()->error('Something went wrong !!!');
        }
        return redirect()->route('asset.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $resp = $this->asset->delete($id);
            if ($resp) {
                $this->asset->deleteAssetQuantity($id);
                $this->asset->deleteAssetAllocate($id);
            }
            toastr()->success('Asset Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }
}
