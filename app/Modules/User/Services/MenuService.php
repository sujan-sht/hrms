<?php

namespace App\Modules\User\Services;


class MenuService
{

    public function activeMenu($currentRoute, $selectedRoute)
    {
        $active = "";
        if ($currentRoute == $selectedRoute) {
            $active = 'class="active"';
        }

        echo $active;
    }


}
