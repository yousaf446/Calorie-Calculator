<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class Role extends Middleware
{
    /**
     * Verify user role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $roles
     * @return mixed
     */
    public function handle($request, Closure $next, $roles = null)
    {
        $token = $request->bearerToken();

        if (JWTAuth::parseToken()->check()) {
            $user = JWTAuth::parseToken()->authenticate();
            $roles_allowed = explode("|", $roles);
    
            if (in_array($user->role, $roles_allowed)) {
                $request->request->add(['user' => $user]);
                return $next($request);
            }
        }
        return response()->json([
            'message' => 'You are not authorized to access this resource!'],
            Response::HTTP_UNAUTHORIZED);
    }
}
