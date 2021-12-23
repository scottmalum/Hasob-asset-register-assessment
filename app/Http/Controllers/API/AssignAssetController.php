<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateAssignAssetRequest;
use App\Http\Requests\API\UpdateAssignAssetRequest;
use App\Http\Resources\AssignAssetResource;
use App\Repositories\AssignAssetRepository;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\NotifyAssignedUser;
use Exception;

class AssignAssetController extends AppBaseController
{
    private $assignAssetRepository;

    public function __construct(AssignAssetRepository $assignAssetRepo)
    {
        $this->assignAssetRepository = $assignAssetRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $assignAsset = $this->assignAssetRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(AssignAssetResource::collection($assignAsset), 'All Assigned Assets retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAssignAssetRequest $request)
    {
        try {
            //Assign Asset to a user
            $assignAsset = $this->assignAssetRepository->createAssignAsset($request);

            return $this->sendResponse(new AssignAssetResource($assignAsset), 'Asset Assigned successfully');
        } catch (Exception $ex) {
            return $this->sendError($ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $assignAsset = $this->assignAssetRepository->find($id);
        if (!$assignAsset) {
            return $this->sendError('Assigned Asset not found');
        }

        return $this->sendResponse(new AssignAssetResource($assignAsset), 'Assigned Asset Retrieved Successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssignAssetRequest $request, $id)
    {
        if (!$assignAsset = $this->assignAssetRepository->find($id)) {
            return $this->sendError("Assigned Asset with the ID: {$id} is not found");
        }

        $updatedAssignAsset = $this->assignAssetRepository->updateAssignAsset($assignAsset, $request);

        return $this->sendResponse(new AssignAssetResource($updatedAssignAsset), 'Assigned Asset successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asset = $this->assignAssetRepository->find($id);
        if (!$asset) {
            return $this->sendError("Assigned Asset with the ID: {$id} not found");
        }

        $asset->delete();

        return $this->sendSuccess('Assigned Asset Deleted Successfully');
    }
}
