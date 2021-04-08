<?php

namespace App;

use App\Http\Controllers\UniversidadController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',
        'id_universidad',
        'estado'
    ];

    public function universidad() {
        return $this->belongsTo(Universidad::class, 'id_universidad');
    }
}
