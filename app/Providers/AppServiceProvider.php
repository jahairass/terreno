<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route; // Importar Route
use App\Http\Middleware\CheckPermission; // Importar la clase del Middleware

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * (Usaremos este método para el registro temprano del Middleware)
     */
    public function register(): void
    {
        // Registro del Middleware de ruta 'permiso'
        // CRÍTICO: Registrar aquí asegura que esté disponible antes que las rutas
        Route::aliasMiddleware('permiso', CheckPermission::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Puedes dejar el contenido de tu método boot() aquí, si tenías algo más.
        // Pero el registro del alias debe estar en register().
    }
}