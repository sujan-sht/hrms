<?php

namespace App\Modules\Tada\Http\Controllers;

use App\Modules\Tada\Entities\TadaSubType;
use App\Modules\Tada\Http\Requests\TadaTypeRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Modules\Tada\Repositories\TadaTypeInterface;
use App\Traits\LogTrait;
class TadaTypeController extends Controller
{
    use LogTrait;
    protected $tadaType;

    public function __construct(TadaTypeInterface $tadaType)
    {
        $this->tadaType = $tadaType;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $tadaTypes = $this->tadaType->findAll($limit = 50, $filter = request('search_value'));
        return view('tada::tadaType.index', compact('tadaTypes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['isEdit'] = false;
        return view('tada::tadaType.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(TadaTypeRequest $request)
    {
        $data = $request->all();
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        try {
            $tadaType = $this->tadaType->save($data);
            if($tadaType){
                foreach ($data['sub_type_title'] as $title) {
                    if(isset($title)){
                        $subData['tada_type_id'] = $tadaType->id;
                        $subData['sub_type_title'] = $title;
                        $this->tadaType->saveSubType($subData);
                    }
                }
            }
            $logData=[
                'title'=>'New claim request type created',
                'action_id'=>$tadaType->id,
                'action_model'=>get_class($tadaType),
                'route'=>route('tadaType.index')
            ];
            $this->setActivityLog($logData);
            toastr()->success('New Tada Type Created Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        return redirect()->route('tadaType.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('tada::tadaType.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['tadaType'] = $this->tadaType->find($id);
        return view('tada::tadaType.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(TadaTypeRequest $request, $id)
    {
        $data = $request->all();
        $data['updated_by'] = auth()->user()->id;
        try {
            $result = $this->tadaType->update($id, $data);
            if($result){
                $this->tadaType->deleteSubType($id);
                if(isset($data['sub_type_title'])){
                    foreach ($data['sub_type_title'] as $title) {
                        if(isset($title)){
                            $subData['tada_type_id'] = $id;
                            $subData['sub_type_title'] = $title;
                            $this->tadaType->saveSubType($subData);
                        }
                    }
                }
            }
            $tadaType= $this->tadaType->find($id);
            $logData=[
                'title'=>'Claim request type updated',
                'action_id'=>$tadaType->id,
                'action_model'=>get_class($tadaType),
                'route'=>route('tadaType.index')
            ];
            $this->setActivityLog($logData);
            toastr()->success('Tada Type Updated Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        return redirect()->route('tadaType.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->tadaType->delete($id);
            $this->tadaType->deleteSubType($id);
            $logData=[
                'title'=>'Claim request type deleted',
                'action_id'=>null,
                'action_model'=>null,
                'route'=>route('tadaType.index')
            ];
            $this->setActivityLog($logData);
            toastr()->success('Tada Type Deleted Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        return redirect()->back();
    }

    public function addMoreSubType()
    {
        $view = view('tada::tadaType.partial.addMoreSubType')->render();
        return response()->json(['result' => $view]);
    }
    
    public function getSubTypeList(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            $filteredSubTypes = TadaSubType::where('tada_type_id', $data['type_id'])->pluck('sub_type_title', 'id');
            return json_encode($filteredSubTypes);
        }
    }
}
