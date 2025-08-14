<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ring extends Model
{
    use HasFactory; // SoftDeletes;

    // public function color()
    // {
    //     return $this->belongsTo(RingColor::class, 'ring_color');
    // }

    // public function width()
    // {
    //     return $this->belongsTo(RingWidth::class, 'ring_width');
    // }
}
