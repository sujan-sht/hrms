<?php

namespace App\Modules\Appraisal\Repositories;

interface AppraisalInterface
{
    public function getList($appraisee_id);
    public function getAppraisal($appraisee_id);

    public function findAll();

    public function findOne($id);

    public function save($data);

    public function saveRespondents($data);
    public function invitationCodeExist($code);

    public function findByInvitationCode($code);
    public function findAppraisalByInvitationCode($code);
    public function findEmployeeApproval($id);

    public function update($id, $data);

    public function delete($id);
}
