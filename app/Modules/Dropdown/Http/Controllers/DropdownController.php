<?php

namespace App\Modules\Dropdown\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Dropdown\Repositories\FieldInterface;

class DropdownController extends Controller
{
    protected $dropdown;
    protected $field;
    
    public function __construct(DropdownInterface $dropdown, FieldInterface $field)
    {
        $this->dropdown = $dropdown;
        $this->field = $field;
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['field'] = $this->field->findAll($limit= 0);
        return view('dropdown::Dropdown.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['field'] = $this->field->getList(); 
        return view('dropdown::Dropdown.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
         $data = $request->all();
        
         try{
             if($data['dropvalue'] == 'admin'){
                 toastr()->error('** admin ** DropDown value cannot be created');
             }else{
                 $this->dropdown->save($data);
                 toastr()->success('Dropdown Value Created Successfully');
             }

        }catch(\Throwable $e){
            toastr($e->getMessage())->error();
        }
        
        return redirect(route('dropdown.index'));
    }
   
     /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function createField()
    {
        return view('dropdown::Dropdown.createField');
    }
    
     /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function storeField(Request $request)
    {
         $data = $request->all();
        
         try{
             $data['slug']=$this->getSlug($data['title']);
            $this->field->save($data);
            toastr()->success('Dropdown Field Created Successfully');
        }catch(\Throwable $e){
            toastr($e->getMessage())->error();
        }
        
        return redirect(route('dropdown.index'));
    }

    
    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('dropdown::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data['dropdown_val'] = $this->dropdown->find($id);
        $data['field'] = $this->field->getList();   
        return view('dropdown::Dropdown.edit',$data);
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
        
         try{
            $this->dropdown->update($id, $data);
            toastr()->success('Dropdown Value Updated Successfully');
        }catch(\Throwable $e){
            toastr($e->getMessage())->error();
        }
        
        return redirect(route('dropdown.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try{
            $this->dropdown->delete($id);
             toastr()->success('Dropdown Value Deleted Successfully');
        }catch(\Throwable $e){
            toastr($e->getMessage())->error();
        }
      return redirect(route('dropdown.index'));  
    }

    public function getSlug($string)
    {
        $string = strtolower($string);
        $string = html_entity_decode($string);
        $string = str_replace(array('ä', 'ü', 'ö', 'ß'), array('ae', 'ue', 'oe', 'ss'), $string);
        $string = preg_replace('#[^\w\säüöß]#', null, $string);
        $string = preg_replace('#[\s]{2,}#', ' ', $string);
        $string = str_replace(array(' '), array('_'), $string);
        return $string;
    }
}
