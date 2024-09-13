<?php

namespace App\Models;

use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'email', 'telefono'];

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
