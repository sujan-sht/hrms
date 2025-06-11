<?php

namespace App\Modules\FiscalYearSetup\Repositories;

interface FiscalYearSetupInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function findOne($id);

    public function find();
    
    public function findEnglishFiscalYear();

    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function getCurrentFiscalYear();

    public function getFiscalYear();

    public function getFiscalYearList();

}
