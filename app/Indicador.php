<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicador extends Model
{
    use HasFactory;

    public function aspecto() {
        return $this->belongsTo(Aspecto::class, 'id_aspecto');
    }
}
