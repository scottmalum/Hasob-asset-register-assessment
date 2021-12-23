<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class IsUser
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
        $roles = Auth::user()->roles->pluck('name')->toArray();
        if (!in_array('User', $roles)) {
            return $this->sendError('Admin can not perform this operation.');
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
