<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Vendor;
use App\Repositories\BaseRepository;

class VendorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'category_id',
        'phone_number',
        'address',
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

    public function createVendor($data)
    {
        // find category
        $category = Category::findOrFail($data->category_id);

        $vendor = Vendor::create([
            'name' => $data->name,
            'phone_number' => $data->phone_number,
            'address' => $data->address,
            'category_id' => $category->id
        ]);

        return $vendor;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Vendor::class;
    }
}
