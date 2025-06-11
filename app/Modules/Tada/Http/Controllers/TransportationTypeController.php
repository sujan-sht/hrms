<?php

namespace App\Modules\Tada\Http\Controllers;

use App\Modules\Tada\Entities\TransportationType;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TransportationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $lists = TransportationType::latest()->paginate(20);
        return view('tada::transportation.index', compact('lists'));
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        TransportationType::updateOrCreate(
            ['id' => $request->id],
            ['title' => $request->title]
        );
        toastr()->success('Transportation Created Successfully.');
        return back();
    }


    public function delete($id)
    {
      TransportationType::findOrFail($id)->delete();
        toastr()->success('Transportation Deleted Successfully.');
        return back();
    }
}
