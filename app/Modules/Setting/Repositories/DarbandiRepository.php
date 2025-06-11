<?php

namespace App\Modules\Setting\Repositories;

use App\Modules\Setting\Entities\Darbandi;

class DarbandiRepository implements DarbandiInterface
{

    public function findAll()
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            return Darbandi::where('organization_id', optional($authUser->userEmployer)->organization_id)->latest()->paginate(10);
        } else {
            return Darbandi::latest()->paginate(10);
        }
    }
    public function find($id)
    {
        return Darbandi::find($id);
    }

    public function save($data)
    {
        return Darbandi::create($data);
    }

    public function update($id, $data)
    {
        $result = $this->find($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        $result = $this->find($id);
        return $result->delete();
    }
}
