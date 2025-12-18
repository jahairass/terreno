<?php

namespace App\Models; // <-- Asegúrate de que el namespace sea este

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model // <-- Nombre de la clase correcto
{
    use HasFactory;

    protected $table = 'detalle_ventas'; // Nombre de la tabla

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'descripcion', // Opcional, según tu tabla
        'precio_unitario',
        'importe',
    ];

    /**
     * Define los atributos que deben ser casteados a tipos nativos.
     * @var array
     */
    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'importe' => 'decimal:2',
    ];

    /**
     * Relación: Un detalle pertenece a una venta.
     */
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    /**
     * Relación: Un detalle corresponde a un producto.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
    
    //Desactivar timestamps si tu tabla no tiene created_at/updated_at
     public $timestamps = true; 
}