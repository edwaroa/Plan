<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factor extends Model
{
    use HasFactory;

    public function tipoFactor(){
        return $this->belongsTo(TipoFactor::class, 'id_tipo_factor');
    }

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }
}
