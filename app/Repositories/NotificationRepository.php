<?php

namespace App\Repositories;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use App\Models\Notification;
use App\Models\Vendor;
use App\Repositories\BaseRepository;
use Exception;

class NotificationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'category_id',
    ];

    private $destination = "public/notification";

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function unassigned()
    {
        return Asset::where('status', '=', 'unassigned')->get();
    }

    public function depreciating($threshold)
    {
        return Asset::where('quantity', '<', $threshold)->get();
    }

    public function assetInLocation($location_id)
    {
        $location = Location::find($location_id);
        if (!$location) {
            throw new Exception("Location with ID: {$location_id} is not found", 404);
        }

        return [
            'location' => $location,
            'assets' =>  Asset::where('location_id', $location->id)->get()
        ];
    }

    public function valuation()
    {
        $allAssets = Asset::all();
        $priceSummation = collect($allAssets)->reduce(function ($total, $curr) {
            return $total + $curr->purchase_price;
        }, 0);

        return [
            'asset_count' => count($allAssets),
            'total_price' => $priceSummation
        ];
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Notification::class;
    }
}
