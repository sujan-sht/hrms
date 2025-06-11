<?php

namespace App\Modules\Admin\Repositories;

interface SystemReminderInterface
{
    public function findAll($limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC']);

    public function create($data);

    public function getSystemReminder($limit = null);
}
