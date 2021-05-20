<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_facultad',
        'estado'
    ];

    public function facultad() {
        return $this->belongsTo(Facultad::class, 'id_facultad');
    }

    public function planes() {
        return $this->hasMany(Plan::class);
    }
}
