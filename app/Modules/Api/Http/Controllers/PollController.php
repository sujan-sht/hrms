<?php

namespace App\Modules\Api\Http\Controllers;

use App\Modules\Poll\Entities\PollResponse;
use App\Modules\Poll\Repositories\PollOptionRepository;
use App\Modules\Poll\Repositories\PollRepository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PollController extends ApiController
{
    protected $pollOptionObj;

    public function __construct() {
        $this->pollOptionObj = new PollOptionRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {
            $data = [];

            $sort = [
                'by' => 'id',
                'sort' => 'desc'
            ];
            $inputData = $filter = $request->all();
            $pollModels = (new PollRepository())->findAll(null, $filter, $sort);
            $report = [];
            foreach ($pollModels as $pollModel)
            {
                // check for expired
                $isExpired = 'no';
                if($pollModel['expiry_date'] && ($pollModel['expiry_date'] < date('Y-m-d'))){
                    $isExpired = 'yes';
                }

                // check for voted
                $isVotedCheck = (new PollRepository())->checkResponseSubmitted($pollModel->id, optional(auth()->user()->userEmployer)->id);
                if(isset($isVotedCheck) && !empty($isVotedCheck)){
                    $isVoted = 'yes';
                    $votedPollOptionId = $isVotedCheck->poll_option_id;
                }else{
                    $isVoted = 'no';
                    $votedPollOptionId = null;
                }

                $report[$pollModel->id]['poll_id'] = $pollModel->id;
                $report[$pollModel->id]['poll_name'] = $pollModel->question;
                $report[$pollModel->id]['end_date'] = $pollModel->expiry_date;
                $report[$pollModel->id]['isExpired'] = $isExpired;
                $report[$pollModel->id]['isVoted'] = $isVoted;
                $report[$pollModel->id]['voted_poll_option_id'] = $votedPollOptionId;
                $report[$pollModel->id]['total_responses'] = $totalCount = $pollModel->responses->count();

                foreach ($pollModel->options as $pollOption)
                {
                    $count = $pollOption->responses->count() ?? 0;
                    if($totalCount == 0) {
                        $percentage = 0;
                    } else {
                        $percentage = number_format((($count/$totalCount) * 100), 0);
                    }

                    $report[$pollModel->id]['responses'][] = [
                        'poll_option_id' => $pollOption->id,
                        'title' => $pollOption->option,
                        'count' => $count,
                        'percentage' => $percentage . '%'
                    ];
                }
            }
            sort($report);
            $filtered = $collection = collect($report);
            if(isset($inputData['is_expired'])) {
                $filtered = $collection->where('isExpired', $inputData['is_expired'])->values();
            }
            if(isset($inputData['is_voted'])) {
                $filtered = $collection->where('isVoted', $inputData['is_voted'])->values();
            }
            if(isset($inputData['is_expired']) && isset($inputData['is_voted'])) {
                $filtered = $collection->where('isExpired', $inputData['is_expired'])->where('isVoted', $inputData['is_voted']);
            }

            $sorted = $filtered->sortByDesc('poll_id')->values();

            $data['pollDetail'] = $sorted;

            return  $this->respondSuccess($data);

        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }

    /**
     *
     */
    public function castVote(Request $request)
    {
        $inputData = $request->all();

        try {
            $data['pollDetail'] = null;

            $authUser = auth()->user();
            $pollResponseData['employee_id'] = $authUser->emp_id;
            $pollResponseData['poll_id'] = $inputData['poll_id'];
            $pollResponseData['poll_option_id'] = $inputData['poll_option_id'];
            $model = PollResponse::create($pollResponseData);

            if($model) {
                $pollModel = optional($model->poll);
                $pollDetail['poll_id'] = $pollModel->id;
                $pollDetail['poll_name'] = $pollModel->question;

                $isExpired = 'no';
                if($pollModel['expiry_date'] && ($pollModel['expiry_date'] < date('Y-m-d'))){
                    $isExpired = 'yes';
                }
                $pollDetail['isExpired'] = $isExpired;

                $isVoted = (new PollRepository())->checkResponseSubmitted($pollModel->id, optional(auth()->user()->userEmployer)->id);
                if(isset($isVoted) && !empty($isVoted)){
                    $pollDetail['isVoted'] = 'yes';
                }else{
                    $pollDetail['isVoted'] = 'no';
                }

                $pollDetail['voted_poll_option_id'] = $inputData['poll_option_id'];
                $pollDetail['total_responses'] = $totalCount = $pollModel->responses->count();

                foreach ($pollModel->options as $pollOption)
                {
                    $count = $pollOption->responses->count() ?? 0;
                    if($totalCount == 0) {
                        $percentage = 0;
                    } else {
                        $percentage = number_format((($count/$totalCount) * 100), 0);
                    }

                    $pollDetail['responses'][] = [
                        'poll_option_id' => $pollOption->id,
                        'title' => $pollOption->option,
                        'count' => $count,
                        'percentage' => $percentage . '%'
                    ];
                }

                $data['pollDetail'] = $pollDetail;
            }

            return $this->respondSuccess($data);
        } catch (\Throwable $th) {
            //throw $th;
            return $this->respondInvalidQuery($th->getMessage());
        }
    }

     /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('api::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('api::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('api::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
