<?php

namespace App\Modules\Onboarding\Console;

use App\Modules\Admin\Entities\MailSender;
use App\Modules\Onboarding\Entities\Applicant;
use App\Modules\Onboarding\Entities\ManpowerRequisitionForm;
use App\Modules\Setting\Entities\Setting;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PullApplicantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'pull:applicants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to pull the data from MAW website for applicant lists.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $res = $client->request('GET', 'http://mawnepal.com/api/job-applications');
        $decodedResponse = json_decode($res->getBody(), true);
        foreach($decodedResponse['data'] as $data){

            if (isset($data['cv'])) {
                $path = public_path('\uploads\onboarding\applicant\resume');

                $url = $data['cv'];
                $fileName = basename($url);
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                
                if(file_exists($path . $fileName)) {
                    continue;
                }
                
                file_put_contents($path .'/'. $fileName, file_get_contents($url));
            }

            $firstName = null;
            $middleName = null;
            $lastName = null;

            $explodeName = explode(' ', $data['name']);
            $countExp = count($explodeName);
            if ($countExp == 1) {
                $firstName = $explodeName[0];
            } elseif ($countExp == 2) {
                $firstName = $explodeName[0];
                $lastName = $explodeName[1];
            } elseif ($countExp == 3) {
                $firstName = $explodeName[0];
                $middleName = $explodeName[1];
                $lastName = $explodeName[2];
            } elseif ($countExp == 4) {
                $firstName = $explodeName[0];
                $middleName = $explodeName[1] . ' ' . $explodeName[2];
                $lastName = $explodeName[3];
            }
            $mrf = ManpowerRequisitionForm::first();
            if($mrf) {
                $externalId = $data['id'];
                $model = Applicant::where('external_id', $externalId)->first();
                if(!$model) {
                    $model = Applicant::create([
                        'manpower_requisition_form_id' => $mrf ? $mrf->id : NULL,
                        'first_name' => $firstName,
                        'middle_name' => $middleName,
                        'last_name' => $lastName,
                        'email' => $data['email'],
                        'experience' => $data['experience'] == '' ? null : $data['experience'],
                        'expected_salary' => $data['salary'] == '' ? null : $data['salary'],
                        'resume' => $fileName,
                        'external_id' => $externalId,
                        'external_comment' => $data['reply'],
                        'status' => 1
                    ]);
                }
            }
        }
    }
}
