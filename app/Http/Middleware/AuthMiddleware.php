<?php

namespace App\Http\Middleware;
use Tymon\JWTAuth\Facades\JWTAuth;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json([
                  'status' => false,
                  'data' => 'Token Expirado'
                ],401);

            }else if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json([
                    'status' => false,
                    'data' => 'Token Invalido'
                  ],400);
            }else{
                return response()->json([
                    'status' => false,
                    'data' => 'No tiene autorización para entrar',
                    'error' => 'Token es requerido'
                  ],401);
            }
        }
        return $next($request->merge(['user' => $user]));
    }
}
