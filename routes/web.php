<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\LiquidacionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\TipoRetencionController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('socios', SocioController::class)->except('show');
    Route::resource('clientes', ClienteController::class)->except('show');
    Route::get('/facturas/meses/{anio}', [FacturaController::class, 'mesesPorAnio'])
        ->name('facturas.meses')
        ->where('anio', '[0-9]{4}');
    Route::resource('facturas', FacturaController::class)->except('show');
    Route::resource('liquidaciones', LiquidacionController::class)->except('show');
    Route::resource('tipos-retencion', TipoRetencionController::class)->except('show');
    // Caja - Movimientos
    Route::get('/caja/movimientos', [CajaController::class, 'movimientos'])->name('caja.movimientos.index');
    Route::get('/caja/movimientos/crear', [CajaController::class, 'movimientosCreate'])->name('caja.movimientos.create');
    Route::post('/caja/movimientos', [CajaController::class, 'movimientosStore'])->name('caja.movimientos.store');
    Route::get('/caja/movimientos/{movimiento}/editar', [CajaController::class, 'movimientosEdit'])->name('caja.movimientos.edit');
    Route::put('/caja/movimientos/{movimiento}', [CajaController::class, 'movimientosUpdate'])->name('caja.movimientos.update');
    Route::delete('/caja/movimientos/{movimiento}', [CajaController::class, 'movimientosDestroy'])->name('caja.movimientos.destroy');

    // Caja - Movimientos - AJAX: meses por año
    Route::get('/caja/movimientos/meses/{anio}', [CajaController::class, 'movimientosMesesPorAnio'])
        ->name('caja.movimientos.meses')
        ->where('anio', '[0-9]{4}');

    // Caja - Categorías (creación rápida)
    Route::post('/caja/categorias', [CajaController::class, 'categoriasStore'])->name('caja.categorias.store');

    // Caja - Aportes
    Route::get('/caja/aportes', [CajaController::class, 'aportes'])->name('caja.aportes.index');
    Route::get('/caja/aportes/crear', [CajaController::class, 'aportesCreate'])->name('caja.aportes.create');
    Route::post('/caja/aportes', [CajaController::class, 'aportesStore'])->name('caja.aportes.store');
    Route::get('/caja/aportes/{aporte}/editar', [CajaController::class, 'aportesEdit'])->name('caja.aportes.edit');
    Route::put('/caja/aportes/{aporte}', [CajaController::class, 'aportesUpdate'])->name('caja.aportes.update');
    Route::delete('/caja/aportes/{aporte}', [CajaController::class, 'aportesDestroy'])->name('caja.aportes.destroy');

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // Configuración
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion');
    Route::post('/configuracion/logo', [ConfiguracionController::class, 'uploadLogo'])->name('configuracion.logo.upload');
    Route::get('/configuracion/logo/eliminar', [ConfiguracionController::class, 'deleteLogo'])->name('configuracion.logo.delete');

    // Reportes
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/mensual/{anio}/{mes}', [ReporteController::class, 'mensual'])
            ->name('mensual')
            ->where('anio', '[0-9]{4}')
            ->where('mes', '[0-9]{1,2}');
        Route::get('/anual/{anio}', [ReporteController::class, 'anual'])
            ->name('anual')
            ->where('anio', '[0-9]{4}');
        Route::get('/situacion-financiera', [ReporteController::class, 'situacionFinanciera'])
            ->name('situacion-financiera');
    });
});
