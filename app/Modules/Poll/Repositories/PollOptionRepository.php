<?php

namespace App\Modules\Poll\Repositories;

use App\Modules\Poll\Entities\PollOption;

class PollOptionRepository implements PollOptionInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'])
    {
        $result = PollOption::when(array_keys($filter, true), function ($query) use ($filter) {
        })
            ->orderBy($sort['by'], $sort['sort'])
            ->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));

        return $result;
    }

    public function find($id)
    {
        return PollOption::find($id);
    }

    public function save($data)
    {
        return PollOption::create($data);
    }

    public function update($id, $data)
    {
        return PollOption::find($id)->update($data);
    }

    public function delete($id)
    {
        return PollOption::where('poll_id', $id)->delete();
    }

    public function checkAndUpdate($pollOptionData, $poll_id)
    {

        foreach($pollOptionData as $option_id => $option)
        {
            if(isset($option))
            {
                $data['option'] = $option;
                $data['poll_id'] = $poll_id;
                PollOption::updateOrCreate([
                    'id' => $option_id
                ], $data);
            }
        }
    }
}
