<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $table = 'cars';

    protected $fillable = [
        'manufacturer',
        'model',
        'year',
        'mileage',
        'price',
        'photo_url',
    ];

    protected $casts = [
        'year'    => 'integer',
        'mileage' => 'integer',
        'price'   => 'integer',
    ];
}
