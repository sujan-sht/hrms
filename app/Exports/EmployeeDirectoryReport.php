<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EmployeeDirectoryReport implements FromView
{
    protected $column_lists;
    protected $displayAll;
    protected $select_columns;
    protected $employeeModels;
    protected $title;


    public function __construct($data)
    {
        $this->column_lists = $data['column_lists'];
        $this->displayAll = $data['displayAll'];
        $this->select_columns = $data['select_columns'];
        $this->employeeModels = $data['employeeModels'];
        $this->title = $data['title'];
    }

    public function view(): View
    {
        return view('exports.employee-directory-report', [
            'column_lists' => $this->column_lists,
            'displayAll' => $this->displayAll,
            'select_columns' => $this->select_columns,
            'employeeModels' => $this->employeeModels,
            'title' => $this->title,

        ]);
    }
}
