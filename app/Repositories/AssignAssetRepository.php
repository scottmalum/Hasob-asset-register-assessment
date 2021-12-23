<?php

namespace App\Repositories;

use App\Http\Controllers\API\AssignAssetController;
use App\Events\AssetWasAssignedToUserEvent;
use App\Models\AssignAsset;
use App\Models\Asset;
use App\Models\Location;
use App\Models\User;
use App\Models\UserProfile;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Exception;
use PhpParser\Node\Expr\Assign;

class AssignAssetRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'asset_id',
        'user_id',
        'due_date',
        'location_id',
        'quantity',
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

    public function createAssignAsset($input)
    {
        $asset = Asset::find($input->asset_id);
        if (!$asset) {
            throw new Exception("Asset with the ID: {$input->asset_id} not found");
        }

        $user = User::find($input->user_id);
        if (!$user) {
            throw new Exception("User with the ID: {$input->user_id} not found");
        }

        $location = Location::find($input->location_id);
        if (!$location) {
            throw new Exception("Location with the ID: {$input->location_id} not found");
        }

        if (intval($input->quantity) > $asset->quantity) {
            throw new Exception("The quantity you are assigninig is greater than the quantity of this particular asset remaining.");
        }

        $assignAsset = null;
        $userProfile = UserProfile::find($user->id);

        //check if user has already been assigned the exact asset you want to assign
        $alreadyAssignedUser = AssignAsset::where(['user_id' => $user->id, 'asset_id' => $asset->id])->first();
        if ($alreadyAssignedUser) {
            $assignAsset = AssignAsset::find($alreadyAssignedUser->id);
            $assignAsset->quantity += intval($input->quantity);
            $assignAsset->update();
        } else {
            $assignAsset = new AssignAsset();
            $assignAsset->asset_id = $asset->id;
            $assignAsset->user_id = $user->id;
            $assignAsset->location_id = $location->id;
            $assignAsset->quantity = intval($input->quantity);
            $assignAsset->due_date = Carbon::parse($input->due_date);

            // Assign Asset to a user
            $assignAsset->save();
        }



        //set status to Assigned in the Asset table
        $asset->status = 'assigned';
        //update quantity in the asset table
        $asset->quantity -= $input->quantity;
        $asset->save();

        //Event Listener and Notification
        $data = [
            'assignAsset' => $assignAsset,
            'user' => $user,
            'name' => "{$userProfile->last_name} {$userProfile->middle_name} {$userProfile->first_name}",
            'asset_name' => $asset->name,
            'quantity' => $input->quantity,
        ];

        AssetWasAssignedToUserEvent::dispatch($data);

        return $assignAsset;
    }

    public function updateAssignAsset($assignAsset, $data)
    {
        $assignAsset->fill($data->all());
        $assignAsset->due_date = Carbon::parse($data->due_date);

        $assignAsset->save();


        return $assignAsset;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AssignAsset::class;
    }
}
