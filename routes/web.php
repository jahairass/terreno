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

Route::post('cajas/movimiento', [App\Http\Controllers\CajaController::class, 'registrarMovimiento'])
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
    // Listado (mostrar)
    Route::get('empleados', [EmpleadoController::class, 'index'])->name('empleados.index')->middleware('permiso:usuarios,mostrar');
    // Alta (alta)
    Route::get('empleados/create', [EmpleadoController::class, 'create'])->name('empleados.create')->middleware('permiso:usuarios,alta');
    Route::post('empleados', [EmpleadoController::class, 'store'])->name('empleados.store')->middleware('permiso:usuarios,alta');
    // Edición (editar)
    Route::get('empleados/{empleado}/edit', [EmpleadoController::class, 'edit'])->name('empleados.edit')->middleware('permiso:usuarios,editar');
    Route::put('empleados/{empleado}', [EmpleadoController::class, 'update'])->name('empleados.update')->middleware('permiso:usuarios,editar');
    // Eliminación (eliminar)
    Route::delete('empleados/{empleado}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy')->middleware('permiso:usuarios,eliminar');


    // MÓDULO: CARGOS (Alias: cargos)
    // Listado (mostrar)
    Route::get('cargos', [CargoController::class, 'index'])->name('cargos.index')->middleware('permiso:cargos,mostrar');
    // Alta (alta)
    Route::get('cargos/create', [CargoController::class, 'create'])->name('cargos.create')->middleware('permiso:cargos,alta');
    Route::post('cargos', [CargoController::class, 'store'])->name('cargos.store')->middleware('permiso:cargos,alta');
    // Edición (editar) - También aplica a la matriz de permisos
    Route::get('cargos/{cargo}/edit', [CargoController::class, 'edit'])->name('cargos.edit')->middleware('permiso:cargos,editar');
    Route::put('cargos/{cargo}', [CargoController::class, 'update'])->name('cargos.update')->middleware('permiso:cargos,editar');
    // Eliminación (eliminar)
    Route::delete('cargos/{cargo}', [CargoController::class, 'destroy'])->name('cargos.destroy')->middleware('permiso:cargos,eliminar');

    // PERMISOS (Requiere permiso de 'editar' en el módulo 'cargos' para la matriz)
    Route::get('cargos/{cargo}/permisos', [PermisoController::class, 'index'])->name('cargos.permisos.index')->middleware('permiso:cargos,editar');
    Route::put('cargos/{cargo}/permisos', [PermisoController::class, 'update'])->name('cargos.permisos.update')->middleware('permiso:cargos,editar');


    // MÓDULO: CATEGORÍAS
// Listado (mostrar)
Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index')->middleware('permiso:categorias,mostrar');
// Alta (alta)
Route::get('categorias/create', [CategoriaController::class, 'create'])->name('categorias.create')->middleware('permiso:categorias,alta');
Route::post('categorias', [CategoriaController::class, 'store'])->name('categorias.store')->middleware('permiso:categorias,alta');
// Edición (editar)
Route::get('categorias/{categoria}/edit', [CategoriaController::class, 'edit'])->name('categorias.edit')->middleware('permiso:categorias,editar');
Route::put('categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update')->middleware('permiso:categorias,editar');
// Eliminación (eliminar)
Route::delete('categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy')->middleware('permiso:categorias,eliminar');


    // MÓDULO: PRODUCTOS (Alias: productos) - Las rutas de productos también deben protegerse
    // Listado (mostrar)
    Route::get('productos', [ProductoController::class, 'index'])->name('productos.index')->middleware('permiso:productos,mostrar');
    // Alta (alta)
    Route::get('productos/create', [ProductoController::class, 'create'])->name('productos.create')->middleware('permiso:productos,alta');
    Route::post('productos', [ProductoController::class, 'store'])->name('productos.store')->middleware('permiso:productos,alta');
    // Edición (editar)
    Route::get('productos/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit')->middleware('permiso:productos,editar');
    Route::put('productos/{producto}', [ProductoController::class, 'update'])->name('productos.update')->middleware('permiso:productos,editar');
    // Eliminación (eliminar)
    Route::delete('productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy')->middleware('permiso:productos,eliminar');


    // MÓDULO: CLIENTES (Alias: clientes)
    // Listado (mostrar)
    Route::get('clientes', [ClienteController::class, 'index'])->name('clientes.index')->middleware('permiso:clientes,mostrar');
    // Alta (alta)
    Route::get('clientes/create', [ClienteController::class, 'create'])->name('clientes.create')->middleware('permiso:clientes,alta');
    Route::post('clientes', [ClienteController::class, 'store'])->name('clientes.store')->middleware('permiso:clientes,alta');
    // Edición (editar)
    Route::get('clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit')->middleware('permiso:clientes,editar');
    Route::put('clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update')->middleware('permiso:clientes,editar');
    // Eliminación (eliminar)
    Route::delete('clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy')->middleware('permiso:clientes,eliminar');


        // ==========================================================
    // MÓDULO: INVENTARIO
    // ==========================================================

    // Listado de Inventario (mostrar)
    Route::get('inventario', [InventarioController::class, 'index'])
        ->name('inventario.index')
        ->middleware('permiso:inventario,mostrar');

    // Edición de Stock/Límites (editar)
    Route::get('inventario/{producto}/edit', [InventarioController::class, 'edit'])
        ->name('inventario.edit')
        ->middleware('permiso:inventario,editar');

    Route::put('inventario/{producto}', [InventarioController::class, 'update'])
        ->name('inventario.update')
        ->middleware('permiso:inventario,editar');

    // NOTA: No hay ruta 'alta' o 'eliminar' de Inventario porque el stock se maneja
    // a través de Edición (ajustes) o de los módulos de Compras/Ventas.


    // PROVEEDORES (Alias: proveedores)
    Route::get('proveedores', [ProveedorController::class, 'index'])->name('proveedores.index')->middleware('permiso:proveedores,mostrar');
    Route::get('proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create')->middleware('permiso:proveedores,alta');
    Route::post('proveedores', [ProveedorController::class, 'store'])->name('proveedores.store')->middleware('permiso:proveedores,alta');
    Route::get('proveedores/{proveedore}/edit', [ProveedorController::class, 'edit'])->name('proveedores.edit')->middleware('permiso:proveedores,editar');
    Route::put('proveedores/{proveedore}', [ProveedorController::class, 'update'])->name('proveedores.update')->middleware('permiso:proveedores,editar');
    Route::delete('proveedores/{proveedore}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy')->middleware('permiso:proveedores,eliminar');

    // COMPRAS (Alias: compras) <-- RUTAS AÑADIDAS/CORREGIDAS
    Route::get('compras', [CompraController::class, 'index'])->name('compras.index')->middleware('permiso:compras,mostrar');
    Route::get('compras/create', [CompraController::class, 'create'])->name('compras.create')->middleware('permiso:compras,alta');
    Route::post('compras', [CompraController::class, 'store'])->name('compras.store')->middleware('permiso:compras,alta');
    Route::get('compras/{compra}/edit', [CompraController::class, 'edit'])->name('compras.edit')->middleware('permiso:compras,editar');
    Route::put('compras/{compra}', [CompraController::class, 'update'])->name('compras.update')->middleware('permiso:compras,editar');
    Route::delete('compras/{compra}', [CompraController::class, 'destroy'])->name('compras.destroy')->middleware('permiso:compras,eliminar');

    // ==========================================================
    // MÓDULO: GESTIÓN DE CAJA (Alias: cajas)
    // ==========================================================

    // VISTA PRINCIPAL (Mostrar estado actual de la caja)
    Route::get('cajas', [CajaController::class, 'index'])
        ->name('cajas.index')
        ->middleware('permiso:cajas,mostrar');

    // ABRIR CAJA (Acción de 'alta')
    Route::post('cajas/abrir', [CajaController::class, 'abrirCaja'])
        ->name('cajas.abrir')
        ->middleware('permiso:cajas,alta');

    // CERRAR CAJA (Acción de 'eliminar')
    Route::post('cajas/cerrar', [CajaController::class, 'cerrarCaja'])
        ->name('cajas.cerrar')
        ->middleware('permiso:cajas,eliminar');

    // ==========================================================
    // MÓDULO: PUNTO DE VENTA (TPV) (Alias: ventas)
    // ==========================================================

    // Ruta para mostrar la interfaz TPV
    Route::get('tpv', [VentaController::class, 'tpv'])
        ->name('ventas.tpv')
        ->middleware('permiso:ventas,mostrar');

    // Ruta para procesar la venta (recibe JSON desde el TPV)
    Route::post('ventas', [VentaController::class, 'store'])
        ->name('ventas.store')
        ->middleware('permiso:ventas,alta');

    //NUEVA RUTA PARA EL TICKET PDF
    Route::get('ventas/ticket/{venta}', [VentaController::class, 'generarTicketPDF'])
        ->name('ventas.ticket')
        ->middleware('permiso:ventas,mostrar'); // O el permiso que consideres

    Route::get('ventas/imprimir/{venta}', [VentaController::class, 'imprimirTicket'])->name('ventas.imprimir');
});
