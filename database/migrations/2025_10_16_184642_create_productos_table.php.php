<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('productos', function (Blueprint $table) {
$table->id();
$table->foreignId('categoria_id')->constrained('categorias')->cascadeOnUpdate()->restrictOnDelete();
$table->string('nombre');
$table->text('descripcion')->nullable();
$table->decimal('precio', 10, 2);
$table->timestamps();
$table->unique(['categoria_id','nombre']);
});
}
public function down(): void { Schema::dropIfExists('productos'); }
};