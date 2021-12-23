<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AssetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'serial' => $this->serial,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'purchase_price' => intval($this->purchase_price),
            'purchase_date' => $this->purchase_date,
            'warranty_exp_date' => $this->warranty_exp_date,
            'vendor' => $this->vendor->name,
            'category' => $this->category->name,
            'location' => $this->location->name,
            'status' => $this->status,
            'picture_url' => $this->picture_url ? Storage::url($this->picture_url) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
