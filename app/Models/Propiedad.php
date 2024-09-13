<?php

namespace App\Models;

use App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propiedad extends Model
{
    use HasFactory;

    protected $table = 'propiedades';
    
    protected $fillable = ['direccion', 'ciudad', 'descripcion', 'precio'];

        /**
     * Define la relación uno a muchos con el modelo SolicitudVisita.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function solicitudesVisitas()
    {
        return $this->hasMany(SolicitudVisita::class);
    }
}
