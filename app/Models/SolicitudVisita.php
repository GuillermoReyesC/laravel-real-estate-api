<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudVisita extends Model
{
    use HasFactory;


    protected $table = 'solicitudes_visitas';
    protected $fillable = ['persona_id', 'propiedad_id', 'fecha_visita', 'comentarios'];

    public function propiedad()
    {
        return $this->belongsTo(Propiedad::class);
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }
}
