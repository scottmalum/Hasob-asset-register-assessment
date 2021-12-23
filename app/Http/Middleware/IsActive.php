<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class IsActive
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

        $user = UserRepository::findUserByEmail($request->email);
        if (!$user) {
            return $this->sendError('User not found', 404);
        }

        if ($user->is_disabled) {
            return $this->sendError('This account is disabled. Contact the administrator', 403);
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
