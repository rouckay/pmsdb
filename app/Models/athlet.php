<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class athlet extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function box()
    {
        return $this->belongsTo(Box::class);
    }
}
