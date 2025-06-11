<?php

namespace App\Modules\Api\Http\Controllers\PendingRequest;

use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\Attendance\Entities\AttendanceRequest;
use App\Modules\Leave\Repositories\LeaveRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PendingRequestController extends ApiController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $activeUserModel = auth()->user();
            $leaves = ((new LeaveRepository())->getEmployeeLeaves($activeUserModel->emp_id)->where('status', 1))->toArray();
            $attendanceRequests = AttendanceRequest::where('status', 1)
                ->where('employee_id', $activeUserModel->emp_id)
                ->orderBy('id', 'DESC')->get()->map(function ($atd) {
                    $atd->title = ($atd->getType());
                    $atd->type = 'attendance';
                    return $atd;
                })->toArray();
    
            $mergeArray = array_merge($leaves, $attendanceRequests);
            usort($mergeArray, function ($a, $b) {
                return strtotime($a['date']) - strtotime($b['date']);
            });
    
            // $myCollectionObj = collect($mergeArray);

            return $this->respond([
                'status' => true,
                'data' => $mergeArray
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
