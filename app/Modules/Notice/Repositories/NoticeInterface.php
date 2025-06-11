<?php

namespace App\Modules\Notice\Repositories;

interface NoticeInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1]);

    public function find($id);

    public function getLatestNotices($limit);
    public function getNotices($limit);


    public function getList();

    public function save($data);

    public function update($id, $data);

    public function delete($id);

    public function getLatestNotice();

    public function getTodayLatestNotices();

    public function getAllNoticeData($filter = []);

    public function getNoticeForEmployee($department_id);

    public function getNoticeForManager();

    public function upload($file);

    public function getTodayNotices();

    public function getAllNoticesForEmployee();

    public function sendMailNotification($model);
}
