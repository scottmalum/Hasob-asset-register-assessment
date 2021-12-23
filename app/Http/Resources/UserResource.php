<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $profile = $this->profile;

        $userProfile = [
            'id' => $this->id,
            'first_name' => $profile->first_name,
            'middle_name' => $profile->middle_name,
            'last_name' => $profile->last_name,
            'email' => $this->email,
            'verified' => $this->email_verified_at ? true : false,
            'phone' => $profile->phone,
            'office' => $profile->office,
            'designation' => $profile->designation,
            'bio' => $profile->bio,
            'roles' => $this->roles->pluck('name'),
            'picture_url' => $profile->picture_url ? Storage::url($profile->picture_url) : null
        ];

        return $userProfile;
    }
}
