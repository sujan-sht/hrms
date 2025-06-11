<?php
namespace App\Modules\Notification\Repositories;

use App\Modules\Notification\Entities\Notification;

class NotificationRepository implements NotificationInterface
{

    public function findAll($id, $limit = null, $filter = [], $sort = ['by' => 'id', 'sort' => 'DESC'], $status = [0, 1])
    {
        $result = Notification::where('notified_user_id', '=', $id)->orderBy($sort['by'], $sort['sort'])->paginate($limit ? $limit : env('DEF_PAGE_LIMIT', 9999));
        return $result;
    }

    public function find($id)
    {
        return Notification::find($id);
    }

    public function save($data)
    {
        return Notification::create($data);
    }

    public function update($id, $data)
    {
        $result = Notification::find($id);
        return $result->update($data);
    }

    public function delete($id)
    {
        return Notification::destory($id);
    }

    public function findAllNotification($id)
    {
        return Notification::where('notified_user_id', '=', $id)->take(10)->orderBy('id', 'DESC')->get();
    }

    public function countActiveNotification($id)
    {
        return Notification::where('is_read', '=', '0')->where('notified_user_id', '=', $id)->count();
    }

}
