<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\VentaController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Autenticación)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Rutas de Login y Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('cajas/movimiento', [CajaController::class, 'registrarMovimiento'])
    ->name('cajas.movimiento')
    ->middleware('permiso:cajas,editar'); // O el permiso que uses para caja

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Requiere Sesión y Middleware de Permisos)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // 1. DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. PERFIL Y LOGOUT
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ==========================================================
    // MÓDULOS CON PROTECCIÓN GRANULAR (RBAC)
    // ==========================================================

    // MÓDULO: EMPLEADOS (Alias: usuarios)
    Route::get('empleados', [EmpleadoController::class, 'index'])->name('empleados.index')->middleware('permiso:usuarios,mostrar');
    Route::get('empleados/create', [EmpleadoController::class, 'create'])->name('empleados.create')->middleware('permiso:usuarios,alta');
    Route::post('empleados', [EmpleadoController::class, 'store'])->name('empleados.store')->middleware('permiso:usuarios,alta');
    Route::get('empleados/{empleado}/edit', [EmpleadoController::class, 'edit'])->name('empleados.edit')->middleware('permiso:usuarios,editar');
    Route::put('empleados/{empleado}', [EmpleadoController::class, 'update'])->name('empleados.update')->middleware('permiso:usuarios,editar');
    Route::delete('empleados/{empleado}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy')->middleware('permiso:usuarios,eliminar');

    // MÓDULO: CARGOS (Alias: cargos)
    Route::get('cargos', [CargoController::class, 'index'])->name('cargos.index')->middleware('permiso:cargos,mostrar');
    Route::get('cargos/create', [CargoController::class, 'create'])->name('cargos.create')->middleware('permiso:cargos,alta');
    Route::post('cargos', [CargoController::class, 'store'])->name('cargos.store')->middleware('permiso:cargos,alta');
    Route::get('cargos/{cargo}/edit', [CargoController::class, 'edit'])->name('cargos.edit')->middleware('permiso:cargos,editar');
    Route::put('cargos/{cargo}', [CargoController::class, 'update'])->name('cargos.update')->middleware('permiso:cargos,editar');
    Route::delete('cargos/{cargo}', [CargoController::class, 'destroy'])->name('cargos.destroy')->middleware('permiso:cargos,eliminar');

    // PERMISOS
    Route::get('cargos/{cargo}/permisos', [PermisoController::class, 'index'])->name('cargos.permisos.index')->middleware('permiso:cargos,editar');
    Route::put('cargos/{cargo}/permisos', [PermisoController::class, 'update'])->name('cargos.permisos.update')->middleware('permiso:cargos,editar');

    // MÓDULO: CATEGORÍAS
    Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index')->middleware('permiso:categorias,mostrar');
    Route::get('categorias/create', [CategoriaController::class, 'create'])->name('categorias.create')->middleware('permiso:categorias,alta');
    Route::post('categorias', [CategoriaController::class, 'store'])->name('categorias.store')->middleware('permiso:categorias,alta');
    Route::get('categorias/{categoria}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit')->middleware('permiso:categorias,editar');
    Route::put('categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update')->middleware('permiso:categorias,editar');
    Route::delete('categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy')->middleware('permiso:categorias,eliminar');

    // MÓDULO: PRODUCTOS (Alias: productos)
    Route::get('productos', [ProductoController::class, 'index'])->name('productos.index')->middleware('permiso:productos,mostrar');
    Route::get('productos/create', [ProductoController::class, 'create'])->name('productos.create')->middleware('permiso:productos,alta');
    Route::post('productos', [ProductoController::class, 'store'])->name('productos.store')->middleware('permiso:productos,alta');
    Route::get('productos/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit')->middleware('permiso:productos,editar');
    Route::put('productos/{producto}', [ProductoController::class, 'update'])->name('productos.update')->middleware('permiso:productos,editar');
    Route::delete('productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy')->middleware('permiso:productos,eliminar');

    // MÓDULO: CLIENTES (Alias: clientes)
    Route::get('clientes', [ClienteController::class, 'index'])->name('clientes.index')->middleware('permiso:clientes,mostrar');
    Route::get('clientes/create', [ClienteController::class, 'create'])->name('clientes.create')->middleware('permiso:clientes,alta');
    Route::post('clientes', [ClienteController::class, 'store'])->name('clientes.store')->middleware('permiso:clientes,alta');
    Route::get('clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit')->middleware('permiso:clientes,editar');
    Route::put('clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update')->middleware('permiso:clientes,editar');
    Route::delete('clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy')->middleware('permiso:clientes,eliminar');

 // ==========================================================
// MÓDULO: INVENTARIO (LISTA + CREAR + EDITAR + ELIMINAR)
// ==========================================================

Route::get('inventario', [InventarioController::class, 'index'])
    ->name('inventario.index')
    ->middleware('permiso:inventario,mostrar');

Route::get('inventario/create', [InventarioController::class, 'create'])
    ->name('inventario.create')
    ->middleware('permiso:inventario,alta');

Route::post('inventario', [InventarioController::class, 'store'])
    ->name('inventario.store')
    ->middleware('permiso:inventario,alta');

Route::get('inventario/{terreno}/edit', [InventarioController::class, 'edit'])
    ->whereNumber('terreno')
    ->name('inventario.edit')
    ->middleware('permiso:inventario,editar');

Route::put('inventario/{terreno}', [InventarioController::class, 'update'])
    ->whereNumber('terreno')
    ->name('inventario.update')
    ->middleware('permiso:inventario,editar');

// ✅ ELIMINAR
Route::delete('inventario/{terreno}', [InventarioController::class, 'destroy'])
    ->whereNumber('terreno')
    ->name('inventario.destroy')
    ->middleware('permiso:inventario,eliminar');



    // PROVEEDORES (Alias: proveedores)
    Route::get('proveedores', [ProveedorController::class, 'index'])->name('proveedores.index')->middleware('permiso:proveedores,mostrar');
    Route::get('proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create')->middleware('permiso:proveedores,alta');
    Route::post('proveedores', [ProveedorController::class, 'store'])->name('proveedores.store')->middleware('permiso:proveedores,alta');
    Route::get('proveedores/{proveedore}/edit', [ProveedorController::class, 'edit'])->name('proveedores.edit')->middleware('permiso:proveedores,editar');
    Route::put('proveedores/{proveedore}', [ProveedorController::class, 'update'])->name('proveedores.update')->middleware('permiso:proveedores,editar');
    Route::delete('proveedores/{proveedore}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy')->middleware('permiso:proveedores,eliminar');

    // COMPRAS (Alias: compras)
    Route::get('compras', [CompraController::class, 'index'])->name('compras.index')->middleware('permiso:compras,mostrar');
    Route::get('compras/create', [CompraController::class, 'create'])->name('compras.create')->middleware('permiso:compras,alta');
    Route::post('compras', [CompraController::class, 'store'])->name('compras.store')->middleware('permiso:compras,alta');
    Route::get('compras/{compra}/edit', [CompraController::class, 'edit'])->name('compras.edit')->middleware('permiso:compras,editar');
    Route::put('compras/{compra}', [CompraController::class, 'update'])->name('compras.update')->middleware('permiso:compras,editar');
    Route::delete('compras/{compra}', [CompraController::class, 'destroy'])->name('compras.destroy')->middleware('permiso:compras,eliminar');

    // ==========================================================
    // ==========================================================
// MÓDULO: GESTIÓN DE CAJA
// ==========================================================
Route::get('cajas', [CajaController::class, 'index'])
    ->name('cajas.index')
    ->middleware('permiso:cajas,mostrar');


// ✅ Movimiento manual
Route::post('cajas/movimiento', [CajaController::class, 'registrarMovimiento'])
    ->name('cajas.movimiento')
    ->middleware('permiso:cajas,editar');

    // ✅ COBROS (mensualidades, multas, adelantos)
Route::post('cajas/cobro', [CajaController::class, 'registrarCobro'])
    ->name('cajas.cobro')
    ->middleware('permiso:cajas,editar');



    // ==========================================================
    // MÓDULO: PUNTO DE VENTA (TPV)
    // ==========================================================

    Route::get('tpv', [VentaController::class, 'tpv'])
        ->name('ventas.tpv')
        ->middleware('permiso:ventas,mostrar');

    Route::post('ventas', [VentaController::class, 'store'])
        ->name('ventas.store')
        ->middleware('permiso:ventas,alta');

    Route::get('ventas/ticket/{venta}', [VentaController::class, 'generarTicketPDF'])
        ->name('ventas.ticket')
        ->middleware('permiso:ventas,mostrar');

    Route::get('ventas/imprimir/{venta}', [VentaController::class, 'imprimirTicket'])
        ->name('ventas.imprimir')
        ->middleware('permiso:ventas,mostrar');

    // ✅ NUEVA RUTA: CONTRATO PDF
    Route::get('ventas/contrato/{venta}', [VentaController::class, 'contratoPDF'])
        ->name('ventas.contrato')
        ->middleware('permiso:ventas,mostrar');

});
