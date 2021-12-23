<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class IsVerified
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
        $user = Auth::user();

        if (!$user) {
            return $this->sendError('You are not logged in.');
        }

        if (!$user->hasVerifiedEmail()) {
            return $this->sendError('Only verified users can access this resource', 403);
        }

        return $next($request);
    }

    private function sendError($error, $code = 401)
    {
        return Response::json([
            'success' => false,
            'message' => $error,
        ], $code);
    }
}
