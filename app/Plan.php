<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_programa',
        'objetivo_general',
        'objetivos_especificos',
        'fecha_inicio',
        'fecha_final',
        'progreso'
    ];

    public function proyectos() {
        return $this->hasMany(Proyecto::class, 'id_plan');
    }

    public function programa() {
        return $this->belongsTo(Programa::class, 'id_programa');
    }
}
