<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    use HasFactory;

    protected $table = 'movimientos_caja';
    
    protected $fillable = [
        'caja_id',
        'user_id', // <-- ¡CORRECCIÓN! Añadir esta línea
        'tipo', // 'ingreso' o 'egreso'
        'descripcion',
        'monto',
        'metodo_pago',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'monto' => 'decimal:2',
    ];

    /**
     * Obtiene el usuario que registró el movimiento.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: El movimiento pertenece a una caja
    public function caja()
    {
        return $this->belongsTo(Caja::class, 'caja_id');
    }

    
}