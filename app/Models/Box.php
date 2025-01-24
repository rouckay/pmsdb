<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    use HasFactory;

    protected $fillable = [
        'box_number',
        'expire_date',
        'athlete_id',
    ];

    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }
}
