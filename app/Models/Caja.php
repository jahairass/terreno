<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;

    protected $table = 'cajas';

    protected $fillable = [
        'user_id',
        'fecha_hora_apertura',
        'fecha_hora_cierre',
        'saldo_inicial',
        'saldo_final',
        'estado',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'fecha_hora_apertura' => 'datetime',
        'fecha_hora_cierre' => 'datetime',
        'saldo_inicial' => 'decimal:2',
        'saldo_final' => 'decimal:2',
    ];

    // Relación: La caja es abierta por un usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación: Una caja tiene muchos movimientos (ventas, retiros, etc.)
    public function movimientos()
    {
        return $this->hasMany(MovimientoCaja::class)->where('tipo', 'ajuste');
    }
    
}
