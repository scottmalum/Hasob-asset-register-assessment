<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function assignAsset()
    {
        return $this->belongsTo(AssignAsset::class);
    }
}
