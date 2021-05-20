<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_plan',
        'objetivo_general',
        'objetivos_especificos',
        'progreso',
        'peso'
    ];

    public function plan() {
        return $this->belongsTo(Plan::class, 'id_plan');
    }

    public function factores() {
        return $this->hasMany(Factor::class, 'id_proyecto');
    }
}
