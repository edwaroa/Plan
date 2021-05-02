<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_inicio',
        'id_indicador',
        'tiempo_entrega'
    ];

    public function indicador() {
        return $this->belongsTo(Indicador::class, 'id_indicador');
    }

    public function users() {
        return $this->belongsToMany(User::class)->withPivot('id');
    }
}
