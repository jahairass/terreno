<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('permisos', function (Blueprint $table) {
$table->id();
$table->foreignId('cargo_id')->constrained('cargos')->cascadeOnDelete();
$table->foreignId('modulo_id')->constrained('modulos')->cascadeOnDelete();
$table->boolean('mostrar')->default(true);
$table->boolean('alta')->default(false);
$table->boolean('detalle')->default(true);
$table->boolean('editar')->default(false);
$table->boolean('eliminar')->default(false);
$table->timestamps();
$table->unique(['cargo_id','modulo_id']);
});
}
public function down(): void { Schema::dropIfExists('permisos'); }
};