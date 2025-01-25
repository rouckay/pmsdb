<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class athlet extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'father_name',
        'phone_number',
        'photo',
        'fee_id',
        'admission_type',
        'admission_expiry_date',
        'box_id',
        'details',
    ];

    public function box()
    {
        return $this->hasOne(Box::class);
    }
    public function fees()
    {
        return $this->belongsTo(Fee::class, 'fee_id');
    }
}
