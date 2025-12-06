<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    protected $table = 'likes';
    
    protected $fillable = [
        'people_id',
        'type'
    ];

    protected $casts = [
        'people_id' => 'integer'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'people_id');
    }
}
