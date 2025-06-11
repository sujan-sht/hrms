<?php

namespace App\Modules\Appraisal\Http\Controllers;

use App\Modules\Appraisal\Repositories\ScoreInterface;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ScoreController extends Controller
{

    protected $score;

    public function __construct(
        ScoreInterface $score
    ) {
        $this->score = $score;
    }


    public function index()
    {
        $data['fields'] = ['Frequency','Ability','Effectiveness'];
        $data['scores'] = $this->score->findAll()->pluck('score');
        $data['frequencies'] =$this->score->findAll()->pluck('frequency');
        $data['abilities'] =$this->score->findAll()->pluck('ability');
        $data['effectiveness'] =$this->score->findAll()->pluck('effectiveness');
        return view('appraisal::appraisal.index', $data);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        try{
             $this->score->update($request->score,$data);
            return ["status" => 1, "message" =>  "Score Data Created Successfully!"];
        }
        catch(Exception $e)
        {
            return ["status" => 0, "message" =>  "Error while Updating Score Data!"];
        }
    }
}
