<?php

namespace App\Repositories;

use App\Models\UserProfile;
use App\Repositories\BaseRepository;

class UserProfileRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'first_name',
        'middle_name',
        'last_name',
        'phone'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return UserProfile::class;
    }

    public function user()
    {
        return $this->belongsTo(UserRepository::class);
    }
}
