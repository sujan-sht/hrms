<?php

namespace App\Modules\MeetingRoom\Http\Controllers;

use App\Modules\MeetingRoom\Entities\MeetingRoom;
use App\Modules\MeetingRoom\Entities\MeetingRoomDetail;
use App\Modules\MeetingRoom\Http\Requests\MeetingRoomRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class MeetingRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['rooms']= MeetingRoom::paginate(15);
        return view('meetingroom::index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('meetingroom::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(MeetingRoomRequest $request)
    {
        try{
            $input_data=$request->all();
            MeetingRoom::create($input_data);
            toastr()->success('Meeting Room Created Successfully');
        }catch(\Throwable $e){
            toastr()->error($e->getMessage());
        }
        
        return redirect(route('meetingRoom.index'));

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request, $id)
    {
        $filter=$request->all();
        if(!empty($filter)){
            if(setting('calendar_type')=='BS'){
                $filter['date'] = date_converter()->nep_to_eng_convert($request->date);
            }
        }else{
            $filter['date']=today();
        }
        
        $data['room']=MeetingRoom::find($id);
        $data['detailInfo']=MeetingRoomDetail::where('room_id',$id)->where('date',$filter['date'])->get();
        return view('meetingroom::view',$data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data['room']=MeetingRoom::find($id);
        return view('meetingroom::edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(MeetingRoomRequest $request, $id)
    {
        try{
            $input_data=$request->all();
            $room=MeetingRoom::find($id);
            $room->update($input_data);
            toastr()->success('Meeting Room Updated Successfully');
        }catch(\Throwable $e){
            toastr()->error($e->getMessage());
        }
        
        return redirect(route('meetingRoom.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try{
            $room=MeetingRoom::find($id);
            $room->delete();
            toastr()->success('Meeting Room Deleted Successfully');
        }catch(\Throwable $e){
            toastr()->error($e->getMessage());
        }
        
        return redirect(route('meetingRoom.index'));
    }

    public function booking(Request $request)
    {
        try{
            $data=$request->all();
            if(setting('calendar_type') == 'BS'){
                $data['date'] = date_converter()->nep_to_eng_convert($request->date);
            }
            $data['booked_by']=Auth::user()->id;
            MeetingRoomDetail::create($data);
            toastr()->success('Meeting Room Booked Successfully');
        }catch(\Throwable $e){
            toastr()->error($e->getMessage());
        }
        return redirect(route('meetingRoom.index'));
    }

    public function checkBooking(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|integer',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        $startMinutes = $data['start_time'];
        $endMinutes = $data['end_time'];
        if(setting('calendar_type') == 'BS'){
            $engDate = date_converter()->nep_to_eng_convert($data['date']);
        }else{
            $engDate = $data['date'];
        }


        $overlappingBookings = MeetingRoomDetail::where('room_id', $data['room_id'])
            ->where('date', $engDate)
            ->where(function ($query) use ($startMinutes, $endMinutes) {
                $query->where(function ($q) use ($startMinutes, $endMinutes) {
                    $q->where('start_time', '<', $endMinutes)
                    ->where('end_time', '>', $startMinutes);
                })->orWhere(function ($q) use ($startMinutes, $endMinutes) {
                    $q->where('start_time', '>=', $startMinutes)
                    ->where('end_time', '<=', $endMinutes);
                });
            })
            ->exists();

        if ($overlappingBookings) {
            return response()->json(['status' => 'error', 'message' => 'Room is already booked for this time.'], 200);
        }
    
        return response()->json(['status' => 'success', 'message' => 'Room is available for booking.'], 200);
    }

}
