<?php

namespace App\Modules\Employee\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Employee\Entities\Employee;
use Illuminate\Contracts\Support\Renderable;
use App\Modules\Employee\Repositories\DocumentDetailInterface;
use App\Modules\Notification\Entities\Notification;
use App\Modules\User\Entities\User;

class DocumentDetailController extends Controller
{
    protected $documentDetail;

    public function __construct(DocumentDetailInterface $documentDetail)
    {
        $this->documentDetail = $documentDetail;
    }

    public function appendAll(Request $request)
    {
        $data['employeeModel'] = Employee::find($request->emp_id);
        $data['document_details'] = $this->documentDetail->findAll($request->emp_id);
        return view('employee::employee.partial.ajaxlayouts.documentDetailTable', $data)->render();
    }


    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $data['issued_date'] =  setting('calendar_type') == 'BS' ? date_converter()->nep_to_eng_convert($data['issued_date']) : $data['issued_date'];
            $data['expiry_date'] =  setting('calendar_type') == 'BS' ? date_converter()->nep_to_eng_convert($data['expiry_date']) : $data['expiry_date'];

            if ($request->hasFile('document_file')) {
                $data['file'] = $this->documentDetail->uploadDocumentFile($data['document_file']);
            }
            unset($data['document_file']);
            $documentDetail = $this->documentDetail->save($data);

            // check for all hr roles
            if($documentDetail){
                $employee =  Employee::find($data['employee_id']);
                $hrs = User::where('user_type', 'hr')->get();
                if (isset($hrs) && !empty($hrs)) {
                    foreach ($hrs as $hr) {
                        // create notification for hr
                        $notificationData['creator_user_id'] = auth()->user()->id;
                        $notificationData['notified_user_id'] = $hr->id;
                        $notificationData['message'] = $employee->full_name . "'s " . " document details has been updated";
                        $notificationData['link'] = route('employee.view', $employee->id);
                        $notificationData['type'] = 'Document Detail';
                        $notificationData['type_id_value'] = $documentDetail->id;
                        Notification::create($notificationData);
                    }
                }
            }
            return ["status" => 1, "message" =>  "Document Detail Created Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Creating Document Detail!"];
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        try {
            $data['issued_date'] =  setting('calendar_type') == 'BS' ? date_converter()->nep_to_eng_convert($data['issued_date']) : $data['issued_date'];
            $data['expiry_date'] =  setting('calendar_type') == 'BS' ? date_converter()->nep_to_eng_convert($data['expiry_date']) : $data['expiry_date'];

            // if($data->document_file != undefined)
            if ($request->hasFile('document_file')) {
                $data['file'] = $this->documentDetail->uploadDocumentFile($data['document_file']);
            }
            unset($data['document_file']);
            $document = $this->documentDetail->update($request->id, $data);
            // check for all hr roles
            if($document){
                $employee =  Employee::find($data['employee_id']);
                $hrs = User::where('user_type', 'hr')->get();
                if (isset($hrs) && !empty($hrs)) {
                    foreach ($hrs as $hr) {
                        // create notification for hr
                        $notificationData['creator_user_id'] = auth()->user()->id;
                        $notificationData['notified_user_id'] = $hr->id;
                        $notificationData['message'] = $employee->full_name . "'s " . " document details has been updated";
                        $notificationData['link'] = route('employee.view', $employee->id);
                        $notificationData['type'] = 'Document Detail';
                        $notificationData['type_id_value'] = $request->id;
                        Notification::create($notificationData);
                    }
                }
            }
            return ["status" => 1, "message" =>  "Document Detail Updated Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while Updating Document Detail!"];
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->documentDetail->delete($request->id);
            return ["status" => 1, "message" =>  "Document Detail Deleted Successfully!"];
        } catch (Exception $e) {
            return ["status" => 0, "message" =>  "Error while deleting Document Detail!"];
        }
    }
}
