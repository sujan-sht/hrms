<?php

namespace App\Modules\Setting\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Contracts\Support\Renderable;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $query = Activity::with(['causer.userEmployer'])->latest();

    if ($request->filled('from_date')) {
        $fromDate = Carbon::parse($request->from_date)->startOfDay();

        if ($request->filled('to_date')) {
            $toDate = Carbon::parse($request->to_date)->endOfDay();
        } else {
            $toDate = now()->endOfDay();
        }

        $query->whereBetween('created_at', [$fromDate, $toDate]);
    }

    $activities = $query->paginate(100);
        return view('setting::activity.view-log',compact('activities'));
    }

}
