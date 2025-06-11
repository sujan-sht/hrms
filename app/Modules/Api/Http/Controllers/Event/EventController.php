<?php

namespace App\Modules\Api\Http\Controllers\Event;

use App\Modules\Api\Http\Controllers\ApiController;
use App\Modules\Api\Transformers\EventResource;
use App\Modules\Event\Repositories\EventRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EventController extends ApiController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        try {
            $filter = [
                'start' => Carbon::now()->toDateString(),
                'end' => date('Y-m-d', strtotime(Carbon::now() . '+30 days'))
            ];
            $events = (new EventRepository())->findAll(null, $filter);
            $eventData = EventResource::collection($events);
            return $this->respond([
                'status' => true,
                'data' => $eventData
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
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
