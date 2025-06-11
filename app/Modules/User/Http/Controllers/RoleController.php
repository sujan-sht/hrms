<?php

namespace App\Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Modules\Dropdown\Repositories\DropdownInterface;
use App\Modules\User\Repositories\RoleInterface;

class RoleController extends Controller
{
    protected $role;
    /**
     * @var DropdownInterface
     */
    private $dropdown;

    public function __construct(RoleInterface $role, DropdownInterface $dropdown)
    {
        $this->role = $role;
        $this->dropdown = $dropdown;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['role'] = $this->role->findAll($limit = 50);
        $data['user_type'] = $this->dropdown->getUserType('user_type');
        return view('user::role.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['permission'] = [];
        $data['routes'] = $this->getRouteList();
        $data['user_type'] = $this->dropdown->getUserType('user_type');
        return view('user::role.create', $data);
    }

    private function getRouteList()
    {
        $app = app();
        $collection = $app->routes->getRoutes();
        // dd($collection);
        $routeList = [];
        $hiddenRoutes = [
            '/',
            'login',
            'role',
            'role.index',
            'role.create',
            'role.delete',
            'role.update',
            'logout',
            'role.store',
            'role.edit',
            'login-post',
            'change-password',
            'update-password',
            'change-username',
            'Notification.checkLink',
            'ignition.healthCheck',
            'ignition.executeSolution',
            'ignition.shareReport',
            'ignition.scripts',
            'ignition.styles',
            'permission.denied',
            'dashboard',
            'cms',
            'store.attendance',
            "l5-swagger.default.api",
            "l5-swagger.default.docs",
            "l5-swagger.default.asset",
            "l5-swagger.default.oauth2_callback",

            "passport.authorizations.authorize",
            "passport.authorizations.approve",
            "passport.authorizations.deny",

            "passport.token",
            "passport.tokens.index",
            "passport.tokens.destroy",
            "passport.token.refresh",
            "passport.clients.index",
            "passport.clients.store",
            "passport.clients.update",
            "passport.clients.destroy",
            "passport.scopes.index",
            "passport.personal.tokens.index",
            "passport.personal.tokens.store",
            "passport.personal.tokens.destroy",

            "changeNepaliDateFormat",
            "updateAtdCheck",
            "assignNewUser",

            "debugbar.openhandler",
            "debugbar.clockwork",
            "debugbar.assets.css",
            "debugbar.assets.js",
            "debugbar.cache.delete",

        ];

        foreach ($collection as $routes) {

            if ($routes->getName() != null && !in_array($routes->getName(), $hiddenRoutes)) {
                $list = str_replace('.', ' ', $routes->getName());
                $routeList[$routes->getName()] = ucfirst(str_replace(
                    'destroy',
                    'delete',
                    str_replace('index', 'list', $list)
                ));
            }
        }
        // dd($routeList);
        return $routeList;
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        try {

            $role_data = array(
                'name' => $input['name'],
                'user_type' => $input['user_type'],
                'status' => '1'
            );
            $last_id = $this->role->save($role_data);

            if (!empty($input['route_name'])) {
                $route_list = $input['route_name'];

                foreach ($route_list as $key => $val) {
                    $permission_data = array(
                        'role_id' => $last_id->id,
                        'route_name' => $val
                    );

                    $this->role->savePermission($permission_data);
                }
            }

            toastr()->success('Role & Permission Created Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect(route('role.index'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('user::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data['role'] = $this->role->find($id);
        $data['permission'] = $this->role->findPermissionById($id)->toArray();
        $data['user_type'] = $this->dropdown->getUserType('user_type');
        $data['routes'] = $this->getRouteList();
        return view('user::role.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        try {

            $role_data = array(
                'name' => $input['name'],
                'user_type' => $input['user_type'],
                'status' => '1'
            );

            $this->role->update($id, $role_data);

            $this->role->deletePermission($id);

            $route_list = $input['route_name'];

            foreach ($route_list as $key => $val) {
                $permission_data = array(
                    'role_id' => $id,
                    'route_name' => $val
                );

                $this->role->savePermission($permission_data);
            }

            toastr()->success('Role & Permission Update Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect(route('role.index'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $this->role->delete($id);
            $this->role->deletePermission($id);

            toastr()->success('Role & Permission Deleted Successfully');
        } catch (\Throwable $e) {
            toastr()->error($e->getMessage());
        }

        return redirect(route('role.index'));
    }
}
