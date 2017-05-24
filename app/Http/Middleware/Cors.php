<?php
/**
 * Created by PhpStorm.
 * User: fabri_000
 * Date: 10/05/2017
 * Time: 17:30
 */

namespace App\Http\Middleware;


use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request)
            //->header('Access-Control-Allow-Origin', 'http://localhost:3000')
            // Depending of your application you can't use '*'
            // Some security CORS concerns
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Max-Age', '10000')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
    }
}