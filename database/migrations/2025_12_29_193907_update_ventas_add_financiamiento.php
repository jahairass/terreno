<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {

            // ✅ NUEVO: Terreno
            $table->unsignedBigInteger('terreno_id')->nullable()->after('id');

            // ✅ NUEVO: Fecha de compra (además de fecha_hora)
            $table->date('fecha_compra')->nullable()->after('fecha_hora');

            // ✅ NUEVO: Financiamiento
            $table->integer('mensualidades')->nullable()->after('total'); // 12,24,36,48,60
            $table->decimal('pago_inicial', 10, 2)->nullable()->after('mensualidades'); // 2500 o 5000
            $table->decimal('monto_mensual', 10, 2)->nullable()->after('pago_inicial');

            // ✅ NUEVO: Día de pago y primer pago
            $table->tinyInteger('dia_pago')->nullable()->after('monto_mensual'); // 15-20
            $table->date('fecha_primer_pago')->nullable()->after('dia_pago'); // compra + 5 días

            // ✅ NUEVO: Estado del contrato/venta
            $table->string('estado_venta', 20)->default('tpv')->after('fecha_primer_pago');
            // tpv | financiado | liquidado | cancelado
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn([
                'terreno_id',
                'fecha_compra',
                'mensualidades',
                'pago_inicial',
                'monto_mensual',
                'dia_pago',
                'fecha_primer_pago',
                'estado_venta',
            ]);
        });
    }
};
