<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago_ventas', function (Blueprint $table) {
            $table->id();

            // Relación con ventas
            $table->foreignId('venta_id')
                ->constrained('ventas')
                ->cascadeOnDelete();

            // Datos del pago
            $table->unsignedInteger('numero_pago'); // 1..N
            $table->date('fecha_vencimiento');
            $table->decimal('monto', 10, 2);

            // Estado
            $table->enum('estado', ['pendiente', 'pagado', 'vencido'])
                ->default('pendiente');

            // Cuando se paga
            $table->date('fecha_pago')->nullable();
            $table->string('referencia')->nullable();

            $table->timestamps();

            // Evita duplicados: pago #1, #2, etc de la misma venta
            $table->unique(['venta_id', 'numero_pago']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago_ventas');
    }
};