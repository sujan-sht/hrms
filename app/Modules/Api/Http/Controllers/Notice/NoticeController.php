<?php

namespace App\Modules\Api\Http\Controllers\Notice;

use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\Api\Transformers\NoticeResource;
use App\Modules\Notice\Entities\Notice;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NoticeController extends ApiController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $query = Notice::query();
            // $query->where('notice_date', '>=', Carbon::now()->toDateString());
            $query->when(true, function ($query) {
                if (auth()->user()->user_type == 'division_hr') {
                    $divisionHrList = [1 => 'Admin'];
                    $divisionHrList = $divisionHrList + (employee_helper()->getParentUserList(['division_hr', 'supervisor']));
                    $divisionHrList = $divisionHrList + (employee_helper()->getParentUserList(['hr'], false));
                    $query->whereIn('created_by', array_keys($divisionHrList));
                }
    
                if (in_array(auth()->user()->user_type, ['employee', 'supervisor'])) {
                    $divisionHrList = [1 => 'Admin'];
                    $divisionHrList = $divisionHrList + (employee_helper()->getParentUserList(['division_hr', 'supervisor']));
                    $divisionHrList = $divisionHrList + (employee_helper()->getParentUserList(['hr'], false));
                    $query->whereIn('created_by', array_keys($divisionHrList));
    
                    $query->where(function ($q) {
                        $q->doesnthave('departments');
        
                        $q->orWhereHas('departments', function ($qry) {
                            $qry->where('department_id',optional(auth()->user())->userEmployer->department_id);
                        });
                    });
                }
            });
            $notices = $query->orderBy('notice_date', 'desc')->get();
            $data = [
                'notices' =>  NoticeResource::collection($notices),
            ];
            return $this->respond([
                'status' => true,
                'data' => $data
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('api::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('api::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('api::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
