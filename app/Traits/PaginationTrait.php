<?php

namespace App\Traits;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use \Illuminate\Support\Facades\Request;

trait PaginationTrait
{
    function paginate($items, $perPage = 10, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $total = count($items);
        $currentpage = $page;
        $offset = ($currentpage * $perPage) - $perPage;
        $itemstoshow = array_slice($items, $offset, $perPage);


        return new LengthAwarePaginator(
            $itemstoshow,
            $total,
            $perPage,
            $page,
            ['path' => Request::fullUrl()]
        );
    }
}
