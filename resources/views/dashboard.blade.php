@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Panel de Control Principal</h2>
    <p class="lead text-secondary">
        Bienvenido, {{ Auth::user()->name }}. Tu cargo actual es:
        <span class="badge bg-info text-dark">{{ Auth::user()->cargo->nombre ?? 'N/A' }}</span>.
    </p>
    <h4 class="mt-5 mb-3">Accesos Directos a Módulos</h4>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        {{-- =============================================== --}}
        {{-- ACCESOS PARA CAJERO (Y OTROS ROLES CON PERMISO) --}}
        {{-- =============================================== --}}
        {{-- Módulo 1: Punto de Venta (POS) --}}
        @if (Auth::user()->hasPermissionTo('ventas', 'mostrar'))
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title text-success"><i class="fas fa-cash-register me-2"></i> Punto de Venta (POS)</h5>
                        <p class="card-text">Inicia una nueva venta, escanea productos y procesa los pagos de los clientes.</p>
                        <a href="{{ route('ventas.tpv') }}" class="btn btn-sm btn-outline-success">Ir a Ventas →</a>
                    </div>
                </div>
            </div>
        @endif
        {{-- Módulo 2: Flujo de Caja --}}
        @if (Auth::user()->hasPermissionTo('cajas', 'mostrar'))
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title text-success"><i class="fas fa-dollar-sign me-2"></i> Flujo de Caja</h5>
                        <p class="card-text">Realiza aperturas, cierres y consulta los movimientos de caja del día.</p>
                        <a href="{{ route('cajas.index') }}" class="btn btn-sm btn-outline-success">Ir a Caja →</a>
                    </div>
                </div>
            </div>
        @endif
        {{-- =============================================== --}}
        {{-- ACCESOS SOLO PARA ADMINISTRADORES --}}
        {{-- =============================================== --}}
        {{-- Módulo 3: Categorías (Módulo: productos) --}}
        @if (Auth::user()->hasPermissionTo('productos', 'mostrar'))
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><i class="fas fa-tags me-2"></i> Catálogo de Categorías</h5>
                        <p class="card-text">Define y organiza los grupos de productos (Básica, Media, Premium).</p>
                        <a href="{{ route('categorias.index') }}" class="btn btn-sm btn-outline-primary">Ir a Categorías →</a>
                    </div>
                </div>
            </div>
        @endif
        {{-- Módulo 4: Empleados (Módulo: usuarios) --}}
        @if (Auth::user()->hasPermissionTo('usuarios', 'mostrar'))
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><i class="fas fa-users me-2"></i> Gestión de Empleados</h5>
                        <p class="card-text">Crea, edita y gestiona las cuentas de acceso de todo el personal.</p>
                        <a href="{{ route('empleados.index') }}" class="btn btn-sm btn-outline-primary">Ir a Empleados →</a>
                    </div>
                </div>
            </div>
        @endif
        {{-- Módulo 5: Cargos y Permisos (Módulo: cargos) --}}
        @if (Auth::user()->hasPermissionTo('cargos', 'mostrar'))
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><i class="fas fa-id-badge me-2"></i> Cargos y Permisos</h5>
                        <p class="card-text">Administra los roles de usuario y sus permisos de acceso (RBAC).</p>
                        <a href="{{ route('cargos.index') }}" class="btn btn-sm btn-outline-primary">Ir a Cargos →</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
