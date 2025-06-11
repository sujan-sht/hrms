<?php

namespace App\Modules\Notification\Http\Controllers;

use App\Modules\Notification\Entities\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Modules\Notification\Repositories\NotificationInterface;


class NotificationController extends Controller
{
    protected $notification;

    public function __construct(NotificationInterface $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $user = Auth::user();
        $id = $user->id;

        $data['notification'] = $this->notification->findAll($id);

        if ($user->user_type == 'super_admin' || $user->user_type == 'admin' || $user->user_type == 'hr') {
            return view('notification::index', $data);
        } else {
            return view('notification::employee.index', $data);
        }
    }

    public function checkLink(Request $request)
    {

        $input = $request->all();
        $notification_id = $input['notification_id'];

        $notification = $this->notification->find($notification_id);

        if ($notification) {
            $link = $notification->link;
            $notice_data =  array(
                'is_read' => '1'
            );
            $this->notification->update($notification_id, $notice_data);

            return redirect($link);
        } else {
            return redirect(route('dashboard'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('notification::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function markAsReadAll(Request $request)
    {
        try {
            $user = auth()->user();
            Notification::where('notified_user_id', '=', $user->id)->where('is_read', 0)->update(['is_read' => 1]);
            toastr()->success('Notification Marked Succesfully');
        } catch (\Throwable $th) {
            toastr()->success($th->getMessage());
        }
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('notification::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('notification::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
