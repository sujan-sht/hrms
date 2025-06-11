<?php

namespace App\Modules\Setting\Http\Controllers;

use App\Modules\Setting\Entities\District;
use App\Modules\Setting\Entities\ProvincesDistrict;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProvinceSetupController extends Controller
{

    protected $provinceSetup;

    public function __construct(
        // ProvinceSetupInterface $provinceSetup,
        // ProvinceSetupInterface $provinceSetup,

    ) {
        // $this->provinceSetup = $provinceSetup;

    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $provinceDistrictQuery = ProvincesDistrict::query();
        if ($request->has('id')) {
            $provinceDistrictQuery->whereIn('id', $request->id);
        }
        if ($request->has('district_id')) {
            $provinceDistrictQuery->where(function($query) use ($request) {
                foreach ($request->district_id as $districtId) {
                    $query->orWhereJsonContains('district_id', $districtId);
                }
            });
        }
        $data['provinceDistricts'] = $provinceDistrictQuery->get();
        $data['districts'] = District::all()->keyBy('id');
        if ($request->has('district_id')) {
            $data['districts'] = District::whereIn('id', $request->district_id)->get()->keyBy('id');
        }

        $data['districtList'] = District::select('id','district_name')->pluck('district_name','id');
        $data['province'] = ProvincesDistrict::select('id', 'title')->groupBy('id', 'title')->pluck('title', 'id');
        return view('setting::province-setup.index', $data)->with('request', $request);
    }

    public function getDistrictsByProvince(Request $request)
    {
        $provinceDistricts = ProvincesDistrict::whereIn('id', $request->province_ids)->get();

        $districtIds = [];
        foreach ($provinceDistricts as $provinceDistrict) {
            $districtIds = array_merge($districtIds, json_decode($provinceDistrict->district_id, true));
        }

        $districts = District::whereIn('id', $districtIds)->get();
        return response()->json(['districts' => $districts]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

        $data['districtList'] = District::select('id','district_name')->pluck('district_name','id');
        return view('setting::province-setup.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'nullable|string',
                'district_id' => 'nullable|array',
            ]);

            // $this->provinceSetup->save($data);
            ProvincesDistrict::create($data);
            toastr()->success('Provinces Created Successfully');
        } catch (\Throwable $e) {
            Log::info('info',['msg'=>$e]);
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('province-setup.index'));
    }


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['province'] = ProvincesDistrict::find($id);
        $data['districtList'] = District::select('id','district_name')->pluck('district_name','id');
        return view('setting::province-setup.edit', $data);
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

            $provinceDistrict = ProvincesDistrict::findOrFail($id);
            $provinceDistrict->update($data);
            toastr()->success('Province Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('province-setup.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {

            $provinceDistrict = ProvincesDistrict::findOrFail($id);
            $provinceDistrict->delete();

            toastr()->success('Province Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('province-setup.index'));
    }

}
