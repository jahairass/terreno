<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoVenta extends Model
{
    protected $table = 'pago_ventas';

    protected $fillable = [
        'venta_id',
        'numero_pago',
        'fecha_vencimiento',
        'monto',
        'estado',
        'fecha_pago',
        'referencia',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'fecha_pago' => 'date',
        'monto' => 'decimal:2',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
