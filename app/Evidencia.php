<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidencia extends Model
{
    use HasFactory;

    public function usuario() {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function actividad() {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }
}
