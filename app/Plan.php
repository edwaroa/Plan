<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    public function programa() {
        return $this->belongsTo(Programa::class, 'id_programa');
    }
}
