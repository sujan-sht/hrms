<?php

namespace App\Modules\Tada\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Modules\Tada\Repositories\BillTypeInterface;

class BillTypeController extends Controller
{
    protected $billType;

    public function __construct(BillTypeInterface $billType)
    {
        $this->billType = $billType;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $billTypes = $this->billType->findAll($limit = 50, $filter = request('search_value'));
        return view('tada::billType.index', compact('billTypes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('tada::billType.create');
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
            $billType = $this->billType->save($data);
            alertify()->success('New Bill Type Created Successfully.');
        } catch (\Throwable $e) {
            alertify($e->getMessage())->error();
        }

        history()->onCreate($billType->id, 'Tada Bill Type', $billType->title);

        return redirect()->route('billType.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('tada::billType.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $billType = $this->billType->find($id);
        return view('tada::billType.edit', compact('billType'));
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
            $this->billType->update($id, $data);
            alertify()->success('New Bill Type Updated Successfully.');
        } catch (\Throwable $e) {
            alertify($e->getMessage())->error();
        }

        history()->onUpdate($id, 'Tada Bill Type', $data['title']);

        return redirect()->route('billType.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $billType = $this->billType->find($id);
            $this->billType->delete($id);
            alertify()->success('Bill Type Deleted Successfully.');
        } catch (\Throwable $e) {
            alertify($e->getMessage())->error();            
        }

        if ($billType) {
            history()->onDelete($id, 'Tada Bill Type', $billType->title);
        }

        return redirect()->back();
    }
}
