<?php

namespace App\Modules\Event\Repositories;

interface EventInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function getList();

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function saveTaggedUser($event,$data=[]);

    public function getLatestEvent();

    public function getUpcomingEvents();

    public function getEmployeeUserList($eventID);

    public function holidayEvents($limit = null, $filter = []);

    public function checkEventByDate($date);

    public function datewiseeventlist($date, $calender_type = 0);

    public function sendMailNotification($model);

    public function getEventByUserType();

}
