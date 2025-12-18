<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Modulo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];
    protected $table = 'modulos';

    // Un Módulo tiene muchos Permisos
    public function permisos()
    {
        return $this->hasMany(Permiso::class);
    }
}
