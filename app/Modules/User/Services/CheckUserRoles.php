<?php

namespace App\Modules\User\Services;


use \App\Modules\User\Entities\User;
use App\Modules\User\Repositories\UserInterface;
use Illuminate\Support\Facades\Auth;

class CheckUserRoles
{

    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function assignedRoles($currentRoute = '')
    {
        $user = Auth::user();
        if ($user->user_type == 'super_admin') {
            return true;
        }
        $userRoutes = [];
        
        $userinfo =  User::find($user->id); 

        foreach ($userinfo->role as $roles) {

            foreach ($roles->permission as $permission) {
                $userRoutes[] = $permission->route_name;
            }
        }

        $defaultRoutes = ['login', 'logout', 'dashboard'];
        $userAllowRoutes = array_merge($userRoutes, $defaultRoutes);
        if (in_array($currentRoute, $userAllowRoutes)) {
            return true;
        }

        return false;

        // return can($currentRoute);
    }
}
