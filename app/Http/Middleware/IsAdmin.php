<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class IsAdmin
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
        if (!in_array('Admin', $roles)) {
            return $this->sendError('You are not authorized to perform this action');
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
