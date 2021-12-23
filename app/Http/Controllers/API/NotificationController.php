<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AssetResource;
use App\Repositories\NotificationRepository;
use Exception;

class NotificationController extends AppBaseController
{
    private $notificationRepository;

    public function __construct(NotificationRepository $notificationRepo)
    {
        $this->notificationRepository = $notificationRepo;
    }

    /**
     * Fetch All Unassigned Asset
     *
     * @return \Illuminate\Http\Response
     */
    public function unassignedAssets()
    {
        $assets = $this->notificationRepository->unassigned();
        return $this->sendResponse(AssetResource::collection($assets), 'All unassigned assets');
    }

    /**
     * Fetch depreciating assets
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function depreciatingAssets($threshold = 2)
    {
        $assets = $this->notificationRepository->depreciating($threshold);
        return $this->sendResponse(AssetResource::collection($assets), 'User details');
    }

    /**
     * Fetch assets in a specified location
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assetInLocation($location_id)
    {
        try {
            $assets =  $this->notificationRepository->assetInLocation($location_id);
            return $this->sendResponse($assets, 'All assets in a location');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }

    public function assetValuation()
    {
        return $this->sendResponse($this->notificationRepository->valuation(), 'Asset Valuation');
    }
}
