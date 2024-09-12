<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model
{
    use HasFactory;
    
    protected $fillable = ['direccion', 'ciudad', 'precio', 'descripcion'];

    public function solicitudesVisitas()
    {
        return $this->hasMany(SolicitudVisita::class);
    }
}
