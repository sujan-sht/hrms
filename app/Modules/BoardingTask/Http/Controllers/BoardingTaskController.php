<?php

namespace App\Modules\BoardingTask\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\BoardingTask\Entities\BoardingTask;
use App\Modules\BoardingTask\Http\Requests\BoardingTaskRequest;
use App\Modules\BoardingTask\Repositories\BoardingTaskInterface;

class BoardingTaskController extends Controller
{
    protected $boardingTask;

    /**
     * BoardingTaskController constructor.
     * @param BoardingTaskInterface $boardingTask
     */
    public function __construct(
        BoardingTaskInterface $boardingTask
    ) {
        $this->boardingTask = $boardingTask;
    }

    public function index(Request $request)
    {
        $filter = $request->all();

        $data['boardingTaskModels'] = $this->boardingTask->findAll(20, $filter);

        return view('boardingtask::boarding-task.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['isEdit'] = false;
        $data['categoryList'] = BoardingTask::getCategoryList();

        return view('boardingtask::boarding-task.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(BoardingTaskRequest $request)
    {
        $data = $request->all();

        try {
            $this->boardingTask->create($data);
            toastr()->success('Data Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('boardingTask.index'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return redirect(route('boardingTask.index'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['isEdit'] = true;
        $data['boardingTaskModel'] = $this->boardingTask->findOne($id);
        $data['categoryList'] = BoardingTask::getCategoryList();

        return view('boardingtask::boarding-task.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(BoardingTaskRequest $request, $id)
    {
        $data = $request->all();

        try {
            $this->boardingTask->update($id, $data);
            toastr()->success('Data Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect(route('boardingTask.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->boardingTask->delete($id);
            toastr()->success('Data Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error('Something Went Wrong !!!');
        }

        return redirect()->back();
    }
}
