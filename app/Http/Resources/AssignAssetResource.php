<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AssignAssetResource extends JsonResource
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
            'asset_id' => $this->asset_id,
            'user_id' => $this->user_id,
            'assignment_date' => $this->created_at,
            'quantity' => $this->quantity,
            'due_date' => $this->due_date,
            'location_id' => $this->location_id,
            'updated_at' => $this->updated_at
        ];
    }
}
