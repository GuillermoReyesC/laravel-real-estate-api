<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'email', 'telefono'];

    public function solicitudesVisitas()
    {
        return $this->hasMany(SolicitudVisita::class);
    }
}
