<?php

namespace App\Http\Middleware;

use Closure;

class ApiHeader
{
    /**
     * The custom client api header auth
     */
    const BIDHEE_HTTP_HEADER = 'Http-Bidhee-Auth';

    /**
     * Collections of Api Clients
     *
     * @var array
     */
    private $apiClients = [
        'hrms-info-sys',
        'kalika-info-sys'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!in_array($request->header(self::BIDHEE_HTTP_HEADER), $this->apiClients)) {
            return response()
                ->json('Client is not authorized!','401');
        }
        return $next($request);
    }
}
