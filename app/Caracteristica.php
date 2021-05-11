<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'id_factor',
        'peso',
        'progreso'
    ];

    public function factor() {
        return $this->belongsTo(Factor::class, 'id_factor');
    }
}
