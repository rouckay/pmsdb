<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlet_id',
        'fees',
    ];


    public function athlete()
    {
        return $this->belongsTo(Athlet::class);
    }
}
