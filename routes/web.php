<?php

use Illuminate\Support\Facades\Route;

// Controladores públicos
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CuentaController;

// Controladores admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\PedidoController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\SuscriptorController;
use App\Http\Controllers\Admin\NewsletterController;

// ══════════════════════════════════════════════════════════
// RUTAS PÚBLICAS
// ══════════════════════════════════════════════════════════

Route::get('/',             [HomeController::class, 'index'])->name('home');

Route::get('/catalogo/pdf',      [CatalogoController::class, 'pdf'])->name('catalogo.pdf');
Route::get('/catalogo',          [CatalogoController::class, 'index'])->name('catalogo');
Route::get('/catalogo/{id}',     [CatalogoController::class, 'show'])->name('catalogo.show');
Route::get('/carrito',      [CarritoController::class, 'index'])->name('carrito');

// ══════════════════════════════════════════════════════════
// AUTENTICACIÓN
// ══════════════════════════════════════════════════════════

Route::get('/login',   [AuthController::class, 'loginForm'])->name('login');
Route::get('/registro',[AuthController::class, 'registroForm'])->name('registro');

// throttle: máximo 5 intentos de login por minuto por IP
Route::post('/login',   [AuthController::class, 'login'])->middleware('throttle:5,1');

// throttle: máximo 10 registros por minuto por IP
Route::post('/registro',[AuthController::class, 'registro'])->middleware('throttle:10,1');

// ── Recuperar contraseña (clientes) ──────────────────────
Route::get('/recuperar',          [AuthController::class, 'recuperarForm'])->name('password.request');
Route::post('/recuperar',         [AuthController::class, 'enviarReset'])->middleware('throttle:5,1')->name('password.email');
Route::get('/restablecer/{token}',[AuthController::class, 'restablecerForm'])->name('password.reset');
Route::post('/restablecer',       [AuthController::class, 'restablecer'])->middleware('throttle:5,1')->name('password.update');

// Logout seguro con POST (evita CSRF logout)
Route::post('/logout',       [AuthController::class, 'logout'])->name('logout');
Route::post('/logout-admin', [AuthController::class, 'logoutAdmin'])->name('logout.admin');

// ══════════════════════════════════════════════════════════
// MI CUENTA (clientes / suscriptores — guard 'web' por defecto)
// ══════════════════════════════════════════════════════════

Route::prefix('mi-cuenta')->middleware('auth')->group(function () {
    Route::get('/',                 [CuentaController::class, 'index'])->name('cuenta.index');
    Route::get('/pedidos',          [CuentaController::class, 'pedidos'])->name('cuenta.pedidos');
    Route::get('/pedidos/{numero}', [CuentaController::class, 'pedido'])->name('cuenta.pedido');
    Route::post('/pedidos/{numero}/reordenar', [CuentaController::class, 'reordenar'])->name('cuenta.reordenar');
    Route::get('/perfil',           [CuentaController::class, 'perfil'])->name('cuenta.perfil');
    Route::put('/perfil',           [CuentaController::class, 'actualizarPerfil'])->name('cuenta.perfil.update');
    Route::put('/password',         [CuentaController::class, 'actualizarPassword'])->name('cuenta.password.update');
    Route::put('/newsletter',       [CuentaController::class, 'actualizarNewsletter'])->name('cuenta.newsletter.update');
});

// ══════════════════════════════════════════════════════════
// APIs JSON (AJAX desde el frontend)
// ══════════════════════════════════════════════════════════

Route::post('/api/carrito', [CarritoController::class, 'api'])->name('api.carrito');

// throttle: máximo 10 checkouts por minuto por IP
Route::post('/api/checkout', [CheckoutController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('api.checkout');

// throttle: máximo 5 suscripciones por minuto por IP
Route::post('/api/suscribir', [HomeController::class, 'suscribir'])
    ->middleware('throttle:5,1')
    ->name('api.suscribir');

// ══════════════════════════════════════════════════════════
// RUTAS ADMIN (protegidas con middleware)
// ══════════════════════════════════════════════════════════

Route::prefix('admin')->middleware('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    // ── Productos ────────────────────────────────────────
    Route::get('/productos',                 [ProductoController::class, 'index'])->name('admin.productos.index');
    Route::get('/productos/crear',           [ProductoController::class, 'crear'])->name('admin.productos.crear');
    Route::post('/productos/guardar',        [ProductoController::class, 'guardar'])->name('admin.productos.guardar');
    Route::get('/productos/{id}/editar',     [ProductoController::class, 'editar'])->name('admin.productos.editar');
    Route::put('/productos/{id}/actualizar', [ProductoController::class, 'actualizar'])->name('admin.productos.actualizar');
    Route::get('/productos/{id}/toggle',     [ProductoController::class, 'toggle'])->name('admin.productos.toggle');
    Route::delete('/productos/{id}/eliminar',[ProductoController::class, 'eliminar'])->name('admin.productos.eliminar');

    // ── Pedidos ──────────────────────────────────────────
    Route::get('/pedidos',               [PedidoController::class, 'index'])->name('admin.pedidos.index');
    Route::get('/pedidos/{id}',          [PedidoController::class, 'detalle'])->name('admin.pedidos.detalle');
    Route::patch('/pedidos/{id}/estado', [PedidoController::class, 'cambiarEstado'])->name('admin.pedidos.estado');

    // ── Categorías ───────────────────────────────────────
    Route::get('/categorias',                        [CategoriaController::class, 'index'])->name('admin.categorias.index');
    Route::post('/categorias/guardar',               [CategoriaController::class, 'guardar'])->name('admin.categorias.guardar');
    Route::delete('/categorias/{id}/eliminar',       [CategoriaController::class, 'eliminar'])->name('admin.categorias.eliminar');

    // ── Suscriptores ─────────────────────────────────────
    Route::get('/suscriptores',                      [SuscriptorController::class, 'index'])->name('admin.suscriptores.index');
    Route::get('/suscriptores/exportar',             [SuscriptorController::class, 'exportar'])->name('admin.suscriptores.exportar');
    Route::delete('/suscriptores/{id}/eliminar',     [SuscriptorController::class, 'eliminar'])->name('admin.suscriptores.eliminar');

    // ── Newsletter ───────────────────────────────────────
    Route::get('/newsletter',          [NewsletterController::class, 'index'])->name('admin.newsletter.index');
    Route::post('/newsletter/enviar',  [NewsletterController::class, 'enviar'])->name('admin.newsletter.enviar');
});