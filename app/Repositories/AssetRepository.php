<?php

namespace App\Repositories;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use App\Models\Vendor;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Exception;

class AssetRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'category_id',
    ];

    private $destination = "public/assets";

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function createAsset($data)
    {
        $vendor = Vendor::find($data->vendor_id);
        if (!$vendor) {
            throw new Exception("Vendor with ID: {$data->vendor_id} not found", 404);
        }

        $category = Category::find($data->category_id);
        if (!$category) {
            throw new Exception("Category with ID: {$data->category_id} not found");
        }

        $location = Location::find($data->location_id);
        if (!$location) {
            throw new Exception("Location with ID: {$data->location_id} not found");
        }

        $asset = new Asset();
        $asset->name = $data->name;
        $asset->serial = $data->serial;
        $asset->description = $data->description;
        $asset->purchase_price = $data->purchase_price;
        $asset->purchase_date = Carbon::parse($data->purchase_date);
        $asset->warranty_exp_date = $data->warranty_exp_date;
        $asset->status = 'unassigned';
        $asset->quantity = $data->quantity ?? 1;
        $asset->vendor_id = $vendor->id;
        $asset->category_id = $category->id;
        $asset->location_id = $location->id;

        // save asset picture
        if ($data->hasFile('picture_url')) {
            // store file
            $path = $data->file('picture_url')->store($this->destination);
            $asset->picture_url = $path;
        }

        $asset->save();

        return $asset;
    }

    public function updateAsset($asset, $data)
    {
        $asset->fill($data->all());

        if ($data->hasFile('picture_url')) {
            // delete previous picture
            if ($asset->picture_url) {
                Storage::delete($asset->picture_url);
            }

            // store file
            $path = $data->file('picture_url')->store($this->destination);
            $asset->picture_url = $path;
        }

        $asset->save();

        return $asset;
    }

    public function unassigned()
    {
        return Asset::where('status', '=', 'unassigned');
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Asset::class;
    }
}
