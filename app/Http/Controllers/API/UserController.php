<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\RegisterRequest;
use App\Http\Requests\API\UserUpdateAvatarRequest;
use App\Http\Requests\API\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends AppBaseController
{
    private $userRepository;
    private $roleRepository;

    public function __construct(UserRepository $userRepo, RoleRepository $roleRepo)
    {
        $this->userRepository = $userRepo;
        $this->roleRepository = $roleRepo;
    }

    /**
     * Fetch All Users
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->userRepository->getNonAdmins();
        return $this->sendResponse(UserResource::collection($users), 'All users');
    }

    /**
     * Register new user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterRequest $request)
    {
        return $this->register($request, $this->userRepository, $this->roleRepository);
    }

    /**
     * Retrive user info
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if (!$user = $this->userRepository->find($id)) {
            return $this->sendError("User with ID: {$id} is not found");
        }

        return $this->sendResponse(new UserResource($user), 'User details');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        if (!$user = $this->userRepository->find($id)) {
            return $this->sendError("User with ID: {$id} is not found");
        }

        $user->fill($request->all());
        $user->profile->fill($request->all());
        if ($request->has('email') && $user->email_verified_at) {
            return $this->sendError("You can't change an already verified email address");
        }
        $user->save();

        return $this->sendResponse(new UserResource($user), 'User profile successfully updated');
    }

    public function updateAvatar(UserUpdateAvatarRequest $request, $id)
    {
        if (!$user = $this->userRepository->find($id)) {
            return $this->sendError("User with ID: {$id} is not found");
        }

        $destination = "public/users/avatars";
        $profile = $user->profile;

        try {
            // delete previous picture
            if ($profile->picture_url) {
                Storage::delete($profile->picture_url);
            }

            // store file
            $path = $request->file('image')->store($destination);
            $profile->picture_url = $path;
            $profile->save();

            return $this->sendResponse(new UserResource($user), 'Profile picture successfully updated');
        } catch (Exception $e) {
            return $this->sendError('Failed updating profile picture. Please try again.', 500);
        }
    }

    public function disable($id)
    {
        if (!$user = $this->userRepository->find($id)) {
            return $this->sendError("User with ID: {$id} is not found");
        }

        $user->is_disabled = !$user->is_disabled ? true : false;
        $user->save();

        $message = $user->is_disabled ? 'enabled' : 'disabled';
        return $this->sendSuccess("Account successfully {$message}");
    }

    /**
     * Delete User
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$user = $this->userRepository->find($id)) {
            return $this->sendError("User with ID: {$id} is not found");
        }

        DB::beginTransaction();
        try {
            $user->delete($id);
            $user->profile()->delete();
            $this->roleRepository->deleteUserRoles($user->id);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError("Unable to delete user {$e->getMessage()}", 500);
        }

        return $this->sendSuccess('User successfully deleted');
    }
}
