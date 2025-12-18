<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::table('users', function (Blueprint $table) {
$table->foreignId('cargo_id')->nullable()->after('password')->constrained('cargos')->nullOnDelete();
});
}
public function down(): void {
Schema::table('users', function (Blueprint $table) {
$table->dropConstrainedForeignId('cargo_id');
});
}
};