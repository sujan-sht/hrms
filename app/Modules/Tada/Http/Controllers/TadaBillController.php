<?php

namespace App\Modules\Tada\Http\Controllers;

use App\Modules\Tada\Repositories\BillInterface;
use App\Modules\Tada\Repositories\BillTypeInterface;
use App\Modules\Tada\Repositories\TadaInterface;

// Repositories
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

// Validator
use Illuminate\Support\Facades\Validator;

class TadaBillController extends Controller
{
    protected $tadaBill, $billType, $tada;

    public function __construct(BillInterface $tadaBill, BillTypeInterface $billType, TadaInterface $tada)
    {
        $this->tadaBill = $tadaBill;
        $this->billType = $billType;
        $this->tada = $tada;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $bills = $this->tadaBill->findAll($limit = 50, $filter = request('search_value'));
        return view('tada::bill.index', compact('bills'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $billTypes = $this->billType->getList();
        $tadas = $this->tada->getList();
        return view('tada::bill.create', compact('billTypes', 'tadas'));
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

        Validator::make($request->all(), [
            'image_src' => 'mimes:jpg,jpeg,png',
        ])->validate();

        try {
            if ($request->hasFile('image_src')) {
                $bill_file = $request->file('image_src');
                $data['image_src'] = $this->tadaBill->upload($bill_file, 'bill');
            }

            $tadaBill = $this->tadaBill->save($data);

            toastr()->success('Bill created Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        if (isset($tadaBill)) {
            history()->onCreate($tadaBill->id, 'Tada Bill', $data['title']);
        }

        return redirect()->route('tada.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('tada::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $tadaBill = $this->tadaBill->find($id);
        $billTypes = $this->billType->getList();
        $tadas = $this->tada->getList();

        return view('tada::bill.edit', compact('tadaBill', 'billTypes', 'tadas'));
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
            $this->tadaBill->update($id, $data);
            toastr()->success('Bill Updated Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        history()->onUpdate($id, 'Tada Bill', $data['title']);

        return redirect()->route('tadaBill.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $tadaBill = $this->tadaBill->find($id);
            $this->tadaBill->delete($id);
            toastr()->success('TADA Bill Deleted Successfully.');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        if ($tadaBill) {
            history()->onDelete($id, 'Tada Bill', $tadaBill->title);
        }

        return redirect()->back();
    }
}
