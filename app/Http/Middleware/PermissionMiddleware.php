<?php

namespace App\Http\Middleware;

use Closure;
use \App\Modules\User\Entities\User;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $user = $request->user();
            $currentRoute = $request->route()->getName();;
            if ($user->user_type == 'super_admin') {
                return $next($request);
            }
            $userRoutes = [];

            $userinfo =  User::find($user->id);

            foreach ($userinfo->role as $roles) {

                foreach ($roles->permission as $permission) {
                    $userRoutes[] = $permission->route_name;
                }
            }
            $defaultRoutes = ['login', 'logout', 'dashboard', 'permission.denied'];
            $userAllowRoutes = array_merge($userRoutes, $defaultRoutes);
            if (in_array($currentRoute, $userAllowRoutes)) {
                return $next($request);
            } else {
                return redirect(route('permission.denied'));
            }

        }else{
            return redirect(route('login'));

        }
    }
}
