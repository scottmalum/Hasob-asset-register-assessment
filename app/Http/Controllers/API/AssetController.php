<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateAssetRequest;
use App\Http\Requests\API\UpdateAssetRequest;
use App\Http\Resources\AssetResource;
use App\Repositories\AssetRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends AppBaseController
{
    private $assetRepository;

    public function __construct(AssetRepository $assetRepo)
    {
        $this->assetRepository = $assetRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $assets = $this->assetRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(AssetResource::collection($assets), 'All Assets retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAssetRequest $request)
    {
        try {
            //Create Asset
            $asset = $this->assetRepository->createAsset($request);
            return $this->sendResponse(new AssetResource($asset), 'Asset saved successfully');
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
        $asset = $this->assetRepository->find($id);
        if (!$asset) {
            return $this->sendError('Asset not found');
        }

        return $this->sendResponse(new AssetResource($asset), 'Asset Retrieved Successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssetRequest $request, $id)
    {
        if (!$asset = $this->assetRepository->find($id)) {
            return $this->sendError("Asset with ID: {$id} is not found");
        }

        $updatedAsset = $this->assetRepository->updateAsset($asset, $request);

        return $this->sendResponse(new AssetResource($updatedAsset), 'Asset successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asset = $this->assetRepository->find($id);

        if (!$asset) {
            return $this->sendError('Asset not found');
        }

        if ($asset->picture_url) {
            Storage::delete($asset->picture_url);
        }

        $asset->delete();

        return $this->sendSuccess('Asset Deleted Successfully');
    }
}
