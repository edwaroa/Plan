<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aspecto extends Model
{
    use HasFactory;

    public function caracteristica() {
        return $this->belongsTo(Caracteristica::class, 'id_caracteristica');
    }
}
