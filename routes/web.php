<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticacionController;
use App\Http\Controllers\RestauranteController;

// Rutas de Autenticación
Route::get('/login', [AutenticacionController::class, 'showLogin'])->name('login');
Route::post('/login', [AutenticacionController::class, 'login'])->name('login.submit');
Route::post('/logout', [AutenticacionController::class, 'logout'])->name('logout');

Route::get('/register', [AutenticacionController::class, 'showRegister'])->name('register');
Route::post('/register', [AutenticacionController::class, 'register'])->name('register.submit');

// Rutas para usuarios autenticados (Menú y hacer pedidos disponible para todos los roles o clientes)
Route::middleware(['auth'])->group(function () {
    Route::get('/menu', [RestauranteController::class, 'index'])->name('menu');
    Route::post('/pedido', [RestauranteController::class, 'storePedido'])->name('pedido.store');
    Route::post('/pedido/guardar', [RestauranteController::class, 'storePedido'])->name('pedido.guardar');
});

// Rutas exclusivas para Clientes (como el historial personal de compras)
Route::middleware(['auth', 'role:cliente'])->group(function () {
    Route::get('/historial', [RestauranteController::class, 'historial'])->name('historial.index');
    Route::put('/pedido/{id}', [RestauranteController::class, 'updatePedido'])->name('pedido.update');
});

// Rutas exclusivas para el Personal (Cocineros y Meseros) para gestionar el menú
Route::middleware(['auth', 'role:cocinero,mesero'])->group(function () {
    Route::get('/comida/crear', [RestauranteController::class, 'createComida'])->name('comida.create');
    Route::post('/comida/crear', [RestauranteController::class, 'storeComida'])->name('comida.store');
    Route::post('/comida/eliminar/{id}', [RestauranteController::class, 'destroyComida'])->name('comida.destroy');
});

// Rutas para Cocineros (y permitimos también al mesero si necesita supervisar la cocina)
Route::middleware(['auth', 'role:cocinero,mesero'])->group(function () {
    Route::get('/cocina', [RestauranteController::class, 'cocina'])->name('cocina.index');
    Route::post('/pedido/preparar/{id}', [RestauranteController::class, 'marcarPreparado'])->name('pedido.preparar');
});

// Rutas exclusivas para Meseros
Route::middleware(['auth', 'role:mesero'])->group(function () {
    Route::get('/entrega', [RestauranteController::class, 'entrega'])->name('entrega.index');
    Route::post('/pedido/entregar/{id}', [RestauranteController::class, 'marcarEntregado'])->name('pedido.entregar');
});

// Rutas de Recuperación de Contraseña
Route::get('/forgot-password', [AutenticacionController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [AutenticacionController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password', [AutenticacionController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AutenticacionController::class, 'reset'])->name('password.update');