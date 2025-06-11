<?php

namespace App\Modules\Api\Http\Controllers;

use App\Modules\Api\Transformers\MrfResource;
use App\Modules\Onboarding\Repositories\MrfInterface;
use Doctrine\DBAL\Query\QueryException;

class MrfController extends ApiController
{
    private $mrfObj;
    
    public function __construct(
        MrfInterface $mrfObj
    ) {
        $this->mrfObj = $mrfObj;
    }

    public function index() {
        try {
            $filter['status'] = 3;
            $mrfData = $this->mrfObj->findAll(99999, $filter);

            return $this->respond([
                'status' => true,
                'data' => MrfResource::collection($mrfData)
            ]);
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }
    }
}
