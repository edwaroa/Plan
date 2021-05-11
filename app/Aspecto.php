<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aspecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'id_caracteristica',
        'peso',
        'progreso'
    ];

    public function caracteristica() {
        return $this->belongsTo(Caracteristica::class, 'id_caracteristica');
    }
}
