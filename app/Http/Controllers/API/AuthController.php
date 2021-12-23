<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;



/**
 * Class AuthController
 * @package App\Http\Controllers\API
 */

class AuthController extends AppBaseController
{

    private $userRepository;
    private $roleRepository;

    public function __construct(UserRepository $userRepo, RoleRepository $roleRepo)
    {
        $this->userRepository = $userRepo;
        $this->roleRepository = $roleRepo;
    }

    /**
     * request: Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {

        $loginCredentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!$token = auth()->attempt($loginCredentials)) {
            return $this->sendError('Unauthorized', 401);
        }

        $user = auth()->user();
        if (!$user->hasVerifiedEmail()) {
            return $this->sendError('Your account is not verified yet', 403);
        }

        $payload = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => new UserResource($user),
        ];

        return $this->sendResponse($payload, 'Login successful');
    }

    /**
     * RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerAdmin(RegisterRequest $request)
    {
        return $this->register($request, $this->userRepository, $this->roleRepository);
    }

    public function verifyEmail($user_id, Request $request)
    {
        if (!$request->hasValidSignature()) {
            return $this->sendError('Invalid verification url', 243);
        }

        $user = $this->userRepository->find($user_id);
        if (!$user) {
            return $this->sendError('User is not found');
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        } else {
            return $this->sendSuccess('Account is already verified');
        }

        return $this->sendSuccess('Account successfully verified');
    }

    public function resendVerification(Request $request)
    {
        if (!$user = $this->userRepository->findUserByEmail($request->email)) {
            return $this->sendError("User not found");
        }

        if ($user->hasVerifiedEmail()) {
            return $this->sendSuccess('Account is already verified');
        }

        $user->sendEmailVerificationNotification();
        return $this->sendSuccess('Verification link sent successfully.');
    }

    public function me()
    {
        $user = auth()->user();
        return $this->sendResponse(new UserResource($user), 'User profile');
    }
}
