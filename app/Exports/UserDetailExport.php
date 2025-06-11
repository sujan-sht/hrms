<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UserDetailExport implements FromView
{
    protected $users;

    public function __construct($data)
    {
        $this->users = $data['users'];
    }

    public function view(): View
    {
        return view('exports.user-detail-report', [
            'users' => $this->users,
        ]);
    }
}
