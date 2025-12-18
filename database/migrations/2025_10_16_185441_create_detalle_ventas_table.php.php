<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


// detalle_ventas
return new class extends Migration {
public function up(): void {
Schema::create('detalle_ventas', function (Blueprint $table) {
$table->id();
$table->foreignId('venta_id')->constrained('ventas')->cascadeOnDelete();
$table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
$table->unsignedInteger('cantidad');
$table->string('descripcion')->nullable();
$table->decimal('precio_unitario', 10,2);
$table->decimal('importe', 12,2);
$table->timestamps();
});
}
public function down(): void { Schema::dropIfExists('detalle_ventas'); }
};