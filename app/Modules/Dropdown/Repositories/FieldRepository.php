<?php 
namespace App\Modules\Dropdown\Repositories;

use App\Modules\Dropdown\Entities\Field;

class FieldRepository implements FieldInterface
{
    
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'ASC'], $status = [0, 1])
    {
        $result =Field::orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result; 
        
    }
    
    public function find($id){
        return Field::find($id);
    }
    
   public function getList(){  
       $result = Field::pluck('title', 'id');
       return $result;
   }
    
    public function save($data){
        return Field::create($data);
    }
    
    public function update($id,$data){
        $result = Field::find($id);
        return $result->update($data);
    }
    
    public function delete($id){
        return Field::destroy($id);
    }
    
    public function countTotal(){
        return Field::count();
    }

    public function findByTitle($data) {
        return Field::where('title','=',$data)->get();
    }

}