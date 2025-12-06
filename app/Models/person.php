<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'people';
    
    protected $fillable = [
        'name',
        'age',
        'location',
        'pictures'
    ];

    protected $casts = [
        'pictures' => 'array',
        'age' => 'integer'
    ];

    public function likes()
    {
        return $this->hasMany(Likes::class, 'people_id');
    }
}
