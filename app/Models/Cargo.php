<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cargo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    // Un Cargo tiene muchos Usuarios
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'cargo_id');
    }

    // Un Cargo tiene muchos Permisos
    public function permisos(): HasMany
    {
        return $this->hasMany(Permiso::class);
    }
}
