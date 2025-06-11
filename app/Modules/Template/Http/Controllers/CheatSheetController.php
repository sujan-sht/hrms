<?php

namespace App\Modules\Template\Http\Controllers;

use App\Modules\Template\Http\Requests\CheatSheetRequest;
use App\Modules\Template\Repositories\CheatSheetInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CheatSheetController extends Controller
{
    protected $cheatSheet;

    public function __construct(CheatSheetInterface $cheatSheet)
    {
        $this->cheatSheet = $cheatSheet;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['cheatSheets'] = $this->cheatSheet->findAll();
        return view('template::cheat-sheet.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('template::cheat-sheet.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CheatSheetRequest $request)
    {
        try{
            $data = $request->all();
            $this->cheatSheet->create($data);
            toastr('Cheat Sheet Added Successfully','success');
            return redirect()->route('cheatSheet.index');
        }catch(Exception $e){
            toastr('Error While Adding Cheat Sheet','success');
            return redirect()->route('cheatSheet.index');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('template::cheat-sheet.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['cheatSheet'] = $this->cheatSheet->findOne($id);
        return view('template::cheat-sheet.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CheatSheetRequest $request, $id)
    {
        try{
            $data = $request->all();
            $this->cheatSheet->update($id,$data);
            toastr('Cheat Sheet Updated Successfully','success');
            return redirect()->route('cheatSheet.index');
        }catch(Exception $e){
            toastr('Error While Updating Cheat Sheet','success');
            return redirect()->route('cheatSheet.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try{
            $this->cheatSheet->delete($id);
            toastr('Cheat Sheet Deleted Successfully','success');
            return redirect()->route('cheatSheet.index');
        }catch(Exception $e){
            toastr('Error While deleting Cheat Sheet','success');
            return redirect()->route('cheatSheet.index');
        }        
    }
}
