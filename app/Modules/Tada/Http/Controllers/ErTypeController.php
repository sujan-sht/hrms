<?php

namespace App\Modules\Tada\Http\Controllers;

use App\Modules\Tada\Entities\ErType;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ErTypeController extends Controller
{
     public function index()
    {
        $lists = ErType::latest()->paginate(20);
        return view('tada::er-type.index', compact('lists'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        ErType::updateOrCreate(
            ['id' => $request->id],
            ['title' => $request->title]
        );
        toastr()->success('ER Type Created Successfully.');
        return back();
    }


    public function delete($id)
    {
      ErType::findOrFail($id)->delete();
        toastr()->success('ER Type Deleted Successfully.');
        return back();
    }
}
