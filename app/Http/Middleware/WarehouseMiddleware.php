<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WarehouseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->get('user');
        if($user['roll'] != 'warehouse'){
            return response()->json([
                'status' => false,
                'data' => 'No estas autorizado para entrar aqui'
            ], 401);
        }
        return $next($request);
    }
}
