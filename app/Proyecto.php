<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    public function plan() {
        return $this->belongsTo(Plan::class, 'id_plan');
    }
}
