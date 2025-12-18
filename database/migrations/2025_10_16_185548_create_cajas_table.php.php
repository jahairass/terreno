<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('cajas', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained('users')->restrictOnDelete();
$table->timestamp('fecha_hora_apertura');
$table->timestamp('fecha_hora_cierre')->nullable();
$table->decimal('saldo_inicial', 12,2)->default(0);
$table->decimal('saldo_final', 12,2)->default(0);
$table->string('estado')->default('abierta');
$table->timestamps();
});
}
public function down(): void { Schema::dropIfExists('cajas'); }
};