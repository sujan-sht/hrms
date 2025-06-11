<?php

namespace App\Modules\Notice\Http\Controllers;

use App\Modules\Admin\Entities\DateConverter;
use App\Modules\Branch\Repositories\BranchInterface;
use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Notice\Entities\Notice;
use App\Modules\Notification\Repositories\NotificationInterface;
use App\Modules\Project\Repositories\ProjectInterface;
use App\Modules\User\Repositories\UserInterface;
use App\Modules\Notice\Traits\HtmlTableTrait;
use Berkayk\OneSignal\OneSignalFacade;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Modules\Notice\Http\Requests\NoticeRequest;

use App\Modules\Notice\Repositories\NoticeInterface;
use App\Modules\Organization\Repositories\OrganizationInterface;
use App\Modules\Setting\Repositories\DepartmentInterface;
use Carbon\Carbon;
use App\Traits\LogTrait;
class NoticeController extends Controller
{
    use LogTrait;
    use HtmlTableTrait;

    protected $notice;
    protected $dropdown;
    protected $branch;
    protected $department;

    /**
     * @var NotificationInterface
     */
    private $notification;
    /**
     * @var UserInterface
     */
    private $user;
    public $organization_list;


    public function __construct(
        NoticeInterface $notice,
        NotificationInterface $notification,
        UserInterface $user,
        OrganizationInterface $organization_list,
        DropdownInterface $dropdown,
        BranchInterface $branch,
        DepartmentInterface $department

    ) {
        $this->notice = $notice;
        $this->notification = $notification;
        $this->user = $user;
        $this->organization_list = $organization_list;
        $this->dropdown = $dropdown;
        $this->branch = $branch;
        $this->department = $department;

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $sortBy = [
            'by' => 'created_at',
            'sort' => 'DESC',
        ];
        $data['notice'] = $this->notice->findAll($limit = 50, [], $sortBy);
        if (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'hr') {
            return view('notice::notice.index', $data);
        } else {
            return view('notice::notice.employee.index', $data);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['is_edit'] = false;
        $data['organizationList'] = $this->organization_list->getList();
        $data['departmentList'] = $this->department->getList();

        return view('notice::notice.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(NoticeRequest $request)
    {


        try {
            $data = $request->all();
            $postNowFlag = true;
            if ($data['type'] == 1) {
                $postNowFlag = true;
                $data['notice_date'] = Carbon::now()->toDateString();
                $data['notice_time'] = Carbon::now()->toTimeString();
                $data['notice_date_nepali'] = date_converter()->eng_to_nep_convert($data['notice_date']);
            } else {
                $postNowFlag = false;
                $data['notice_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['notice_date_nepali']) : $data['notice_date'];
                $data['notice_date_nepali'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['notice_date']) : $data['notice_date_nepali'];
            }

            // $data['file'] = $request->hasFile('file') ? $this->notice->upload($request->file) : '';
            // $data['image'] = $request->hasFile('image') ? $this->saveFile($request->image)['filename'] : '';



            if ($request->hasFile('image')) {
                $data['image'] = $request->hasFile('image') ? $this->saveFile($request->image)['filename'] : null;
            }

            if (!empty($request->organizationId)) {
                $data['organization_id'] = json_encode($request->organizationId);
            }
            if (!empty($request->departmentArray)) {
                $data['department_id'] = json_encode($request->departmentArray);
            }
            if (!empty($request->branchArray)) {
                $data['branch_id'] = json_encode($request->branchArray);
            }
            if (!empty($request->employeeId)) {
                $data['employee_id'] = json_encode($request->employeeId);
            }
            $data['file']=null;
            $notice = $this->notice->save($data);
            if($notice){
                if ($request->hasFile('file')) {
                    foreach($request->file as $file){
                        $data['file'] = $this->notice->upload($file);
                        $notice->files()->create(['file' => $data['file']]);

                    }
                }
            }
            if ($postNowFlag == true) {
                $this->notice->sendMailNotification($notice);
            }

            //            OneSignalFacade::sendNotificationToAll(
            //                "Notice For All",
            //                $url = null,
            //                $data = $notice,
            //                $buttons = null,
            //                $schedule = null
            //            );

            // $user_list=$this->user->getAllActiveUser();

            /* ---------------------------------------------------
                       Notification Start
           ------------------------------------------------------*/


            // $message = "HR has published a new notice. Kindly check it in the notice section of your dashboard.";
            // $link = route('notice.index');
            // foreach ($user_list as $value){
            //     $notification_data = array(
            //         'creator_user_id' => '1',
            //         'notified_user_id' => $value->id,
            //         'message' => $message,
            //         'link' => $link,
            //         'type' => 'notice',
            //         'type_id_value' => $notice->id,
            //         'is_read' => '0',
            //     );

            //     $this->notification->save($notification_data);
            // }

            /* ---------------------------------------------------
                        Notification End
            ------------------------------------------------------*/
            $logData=[
                'title'=>'New notice created',
                'action_id'=>$notice->id,
                'action_model'=>get_class($notice),
                'route'=>route('notice.view',$notice->id)
            ];
            $this->setActivityLog($logData);
            toastr()->success('Notice Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect(route('notice.index'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('notice::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['notice'] = $this->notice->find($id);
        $data['organization_id'] = json_decode($data['notice']->organization_id);
        $data['branch_id'] = json_decode($data['notice']->branch_id);
        $data['department_id'] = json_decode($data['notice']->department_id);
        $data['employee_id'] = json_decode($data['notice']->employee_id);
        if (!is_null($data['organization_id']) && (!is_null($data['branch_id']) && !is_null($data['department_id']) && !is_null($data['employee_id']))) {
            $data['employeeList'] = Employee::whereIn('organization_id', $data['organization_id'])->whereIn('branch_id', $data['branch_id'])->whereIn('department_id', $data['department_id'])->get()->pluck('full_name', 'id');
        }elseif(!is_null($data['organization_id']) && (!is_null($data['branch_id']))){
            $data['employeeList'] = Employee::whereIn('organization_id', $data['organization_id'])->whereIn('branch_id', $data['branch_id'])->get()->pluck('full_name', 'id');
        }elseif(!is_null($data['organization_id']) && (!is_null($data['department_id']))){
            $data['employeeList'] = Employee::whereIn('organization_id', $data['organization_id'])->whereIn('department_id', $data['department_id'])->get()->pluck('full_name', 'id');
        }elseif(!is_null($data['organization_id']) && (!is_null($data['employee_id']))){
            $data['employeeList'] = Employee::whereIn('id', $data['employee_id'])->get()->pluck('full_name', 'id');
        }
        $data['is_edit'] = true;
        $data['organizationList'] = $this->organization_list->getList();
        $data['branchs']=$this->branch->branchListMultipleOrganizationwise($data['organization_id']);
        $data['departmentList'] = $this->department->getList();
        return view('notice::notice.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(NoticeRequest $request, $id)
    {
        $data = $request->all();

        try {
            // if ($request->hasFile('file')) {
            //     $data['file'] = $this->notice->upload($request->file);
            // }

            // dd($request->all());

            if ($request->hasFile('image')) {
                $data['image'] = $request->hasFile('image') ? $this->saveFile($request->image)['filename'] : null;
            }

            if ($data['type'] == 1) {
                $data['notice_date'] = Carbon::now()->toDateString();
                $data['notice_time'] = Carbon::now()->toTimeString();
                $data['notice_date_nepali'] = date_converter()->eng_to_nep_convert($data['notice_date']);
            } else {
                $data['notice_date'] = setting('calendar_type') == "BS" ? date_converter()->nep_to_eng_convert($data['notice_date_nepali']) : $data['notice_date'];
                $data['notice_date_nepali'] = setting('calendar_type') == "AD" ? date_converter()->eng_to_nep_convert($data['notice_date']) : $data['notice_date_nepali'];
            }

            if (!empty($request->organizationId)) {
                $data['organization_id'] = json_encode($request->organizationId);
            }
            if (!empty($request->branchArray)) {
                $data['branch_id'] = json_encode($request->branchArray);
            }
            if (!empty($request->departmentArray)) {
                $data['department_id'] = json_encode($request->departmentArray);
            }
            if (!empty($request->employeeId)) {
                $data['employee_id'] = json_encode($request->employeeId);
            }
            $data['file'] = null;
            $notice= Notice::find($id);
            $notice->update($data);
            if($notice){
                if ($request->hasFile('file')) {
                    $notice->files()->delete();
                    foreach($request->file as $file){
                        $data['file'] = $this->notice->upload($file);
                        $notice->files()->create(['file' => $data['file']]);

                    }
                }
            }
            $logData=[
                'title'=>'Notice updated',
                'action_id'=>$notice->id,
                'action_model'=>get_class($notice),
                'route'=>route('notice.view',$notice->id)
            ];
            $this->setActivityLog($logData);
            toastr()->success('Notice Updated Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect(route('notice.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $notice=Notice::find($id);
            $notice->files()->delete();
            $this->notice->delete($id);
            $logData=[
                'title'=>'Notice deleted',
                'action_id'=>null,
                'action_model'=>null,
                'route'=>route('notice.index')
            ];
            $this->setActivityLog($logData);
            toastr()->success('Notice Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }
        return redirect(route('notice.index'));
    }

    public function downloadSheet(Request $request)
    {


        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],

        ];

        $year = date('Y');

        $objPHPExcel = new Spreadsheet();
        $worksheet = $objPHPExcel->getActiveSheet();

        // set Header

        $objPHPExcel->getActiveSheet(0)->SetCellValue('A1', 'Notice ID');
        $objPHPExcel->getActiveSheet(0)->SetCellValue('B1', 'Title');
        $objPHPExcel->getActiveSheet(0)->SetCellValue('C1', 'Description');
        $objPHPExcel->getActiveSheet(0)->SetCellValue('D1', 'Notice Date');
        $objPHPExcel->getActiveSheet(0)->SetCellValue('E1', 'Created By');



        $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);


        $notice_data = $this->notice->getAllNoticeData();
        $num = 2;
        foreach ($notice_data as $key => $value) {





            $objPHPExcel->getActiveSheet(0)->SetCellValue('A' . $num, $value['id']);



            if (!empty($value['created_by'])) {
                $created = $this->user->find($value['created_by']);
                $created_by = $created['username'];
            } else {
                $created_by = '';
            }

            $objPHPExcel->getActiveSheet(0)->SetCellValue('B' . $num, $value['title']);
            $objPHPExcel->getActiveSheet(0)->SetCellValue('C' . $num, $value['description']);
            $objPHPExcel->getActiveSheet(0)->SetCellValue('D' . $num, $value['notice_date']);
            $objPHPExcel->getActiveSheet(0)->SetCellValue('E' . $num, $created_by);




            $num++;
        }


        $writer = new Xlsx($objPHPExcel);
        $file = 'notice_' . $year;
        $filename = $file . '.xlsx';
        header('Content-Type: application/openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $writer->save('php://output');

        exit;
    }

    public function view($id)
    {
        $data['notice'] = $this->notice->find($id);
        return view('notice::notice.view', $data);
    }

    public static function saveFile($file)
    {
        $imageName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $size = $file->getSize(); // in bytes

        $fileName = time() . '-' . preg_replace('[ ]', '-', $imageName);
        $file->move(public_path() . '/' . Notice::FILE_PATH, $fileName);

        return [
            'filename' => $fileName,
            'extension' => $extension,
            'size' => $size
        ];
    }

    public function getOrganizationEmployee(Request $request)
    {
        // Retrieve filters from the request
        $filters = $request->only(['organization_id', 'branch_id', 'department_id']);
        // Start with a base query for Employee
        $query = Employee::query();

        // Apply filters conditionally
        if (isset($filters['organization_id'])) {
            $query->whereIn('organization_id', $filters['organization_id']);
        }

        if (isset($filters['branch_id'])) {
            $query->whereIn('branch_id', $filters['branch_id']);
        }

        if (isset($filters['department_id'])) {
            $query->whereIn('department_id', $filters['department_id']);
        }

        // Fetch the employees and pluck full_name and id
        // $employees = $query->get()->pluck('full_name', 'id');
        $employees = $query->whereHas('user', function ($query) {
            // Filter based on the active status of the user
            $query->where('active', 1);
        })->get()->pluck('full_name', 'id');

        // Return the response as JSON
        return response()->json($employees);
    }


    public function getOrganizationBranch(Request $request)
    {
        $filter = $request->all();
        if (isset($filter['organization_id'])){
            $branchs=$this->branch->branchListMultipleOrganizationwise($filter['organization_id']);
        }
        return response()->json($branchs);
    }
}
