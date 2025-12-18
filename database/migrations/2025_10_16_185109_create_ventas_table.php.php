<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


// ventas
return new class extends Migration {
public function up(): void {
Schema::create('ventas', function (Blueprint $table) {
$table->id();
$table->foreignId('cliente_id')->nullable()
      ->constrained('clientes', 'idCli')->nullOnDelete();
$table->foreignId('user_id')->constrained('users')->restrictOnDelete();
$table->timestamp('fecha_hora');
$table->string('metodo_pago');
$table->decimal('total', 12,2)->default(0);
$table->decimal('monto_recibido', 12,2)->default(0);
$table->decimal('monto_entregado', 12,2)->default(0);
$table->timestamps();
});
}
public function down(): void { Schema::dropIfExists('ventas'); }
};