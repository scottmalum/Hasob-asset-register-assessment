<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
