<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas'; // Nombre de la tabla en la base de datos

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'cliente_id',       // ID del cliente (puede ser NULL)
        'user_id',          // ID del usuario (cajero) que hizo la venta
        'fecha_hora',       // Timestamp de la venta
        'metodo_pago',      // Ej: 'efectivo', 'tarjeta'
        'total',            // Monto total de la venta
        'monto_recibido',   // Monto que dio el cliente
        'monto_entregado',  // Cambio que se le dio al cliente
    ];

    /**
     * Define los atributos que deben ser mutados a fechas.
     *
     * @var array
     */
    protected $dates = [
        'fecha_hora' => 'datetime'
    ];

    /**
     * Relación: Una venta pertenece a un cliente (o puede ser NULL).
     */
    public function cliente()
    {
        // La clave foránea en 'ventas' es 'cliente_id',
        // y la clave primaria en 'clientes' es 'idCli'.
        return $this->belongsTo(Cliente::class, 'cliente_id', 'idCli');
    }

    /**
     * Relación: Una venta es realizada por un usuario (cajero).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación: Una venta tiene muchos detalles (productos vendidos).
     */
    public function detalles()
    {
        // La clave foránea en 'detalle_ventas' es 'venta_id'
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }
}