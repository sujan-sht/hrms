<?php

namespace App\Modules\Tada\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

// Repositories
use App\Modules\Tada\Repositories\AllowanceTypeInterface;

class AllowanceTypeController extends Controller
{
    protected $allowanceType;

    public function __construct(AllowanceTypeInterface $allowanceType)
    {
        $this->allowanceType = $allowanceType;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $allowanceTypes = $this->allowanceType->findAll($limit = 50, $filter = request('search_value'));
        return view('tada::allowanceType.index', compact('allowanceTypes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('tada::allowanceType.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        try {
            $allowanceType = $this->allowanceType->save($data);
            toastr()->success('New Allowance Type Created Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        if (isset($allowanceType)) {
            history()->onCreate($allowanceType->id, 'Allowance Type', $allowanceType->title);
        }

        return redirect()->route('allowanceType.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('tada::allowanceType.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $allowanceType = $this->allowanceType->find($id);
        return view('tada::allowanceType.edit', compact('allowanceType'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $data['updated_by'] = auth()->user()->id;

        try {
            $this->allowanceType->update($id, $data);
            toastr()->success('New Allowance Type Updated Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        history()->onUpdate($id, 'Allowance Type', $data['title']);

        return redirect()->route('allowanceType.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $allowanceType = $this->allowanceType->find($id);
            $this->allowanceType->delete($id);
            toastr()->success('Allowance Type Deleted Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        if ($allowanceType) {
            history()->onDelete($allowanceType->id, 'Allowance Type', $allowanceType->title);
        }

        return redirect()->back();
    }
}
