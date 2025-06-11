<?php

namespace App\Modules\Poll\Repositories;

interface PollInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function find($id);

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function checkAndUpdateResponse($data);

    public function getLatestPoll();

    public function deletePollResponse($id);

    public function checkResponseSubmitted($poll_id, $employee_id);

    public function deletePollParticipants($poll_id);

    public function pollAllocation($data, $pollModel);

    public function findPollParticipant($poll_id, $organization_id);
    public function deletePollParticipant($poll_id, $organization_id);
    public function updateOrCreatePollParticipant($inputData);
    public function createPollParticipant($inputData, $organization_id, $pollModel);
}
