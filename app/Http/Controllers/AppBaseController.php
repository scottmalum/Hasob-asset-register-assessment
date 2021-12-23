<?php

namespace App\Http\Controllers;

use App\Events\EmailVerificationEvent;
use App\Http\Requests\API\RegisterRequest;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Response;

class AppBaseController extends Controller
{
    public function sendResponse($result, $message, $code = 200)
    {
        return Response::json(
            [
                'success' => true,
                'data'    => $result,
                'message' => $message,
            ],
            $code
        );
    }

    public function sendError($error, $code = 404)
    {
        return Response::json([
            'success' => false,
            'message' => $error,
        ], $code);
    }

    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ], 200);
    }

    // reusable register
    public function register(RegisterRequest $request, UserRepository $userRepository, RoleRepository $roleRepo, $role = 'Admin')
    {
        $userCredentials = [
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        DB::beginTransaction();

        try {
            // register user and update profile
            $user = $userRepository->create($userCredentials);

            // assign admin role to user
            $assignedRole = $role === "Admin" ? $roleRepo->getAdminRole() : $roleRepo->getUserRole();
            $user->roles()->attach($assignedRole);

            // create user profile
            $user->profile()->create(
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                ]
            );

            // send verification mail to use
            EmailVerificationEvent::dispatch($user);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError("Registration failed, please try again. {$e->getMessage()}", 500);
        }

        return $this->sendSuccess(
            "{$role} registration is successful. A verification link has been sent to {$user->email}"
        );
    }
}
