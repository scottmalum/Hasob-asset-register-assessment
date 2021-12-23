<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'email',
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
        return User::class;
    }

    /**
     * fetch user by email
     */
    public static function findUserByEmail(string $email): User
    {
        return User::where('email', $email)->first();
    }

    /**
     * fetch users that are not admins
     */

    public static function getNonAdmins()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', '<>', 'Admin');
        })->get();
    }
}
