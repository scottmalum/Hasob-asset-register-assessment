<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class RoleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
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
        return Role::class;
    }

    public function getAdminRole()
    {
        return Role::where('name', 'Admin')->first();
    }

    public function getUserRole()
    {
        return Role::where('name', 'User')->first();
    }

    public function deleteUserRoles($user)
    {
        return DB::table('role_user')->where('user_id', '=', $user)->delete();
    }
}
