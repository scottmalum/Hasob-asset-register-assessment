<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'phone',
        'picture_url',
        'office',
        'designation',
        'bio',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
