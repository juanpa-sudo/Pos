<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UserController;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:api'])->group(function () {
    // Rutas de administraccion del usuario

    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    // Rutas de administraccion categorias

    Route::post('/categoria', [CategoriaController::class, 'store'])->name('categoria.store');
    Route::get('/categoria', [CategoriaController::class, 'index'])->name('categoria.index');
    Route::put('/categoria/{categoria}', [CategoriaController::class, 'update'])->name('categoria.update');
    Route::delete('/categoria/{categoria}', [CategoriaController::class, 'destroy'])->name('categoria.destroy');


    // Rutas de administraccion para productos
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos', [ProductoController::class, 'index'])->name('producto.index');
    Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('producto.update');
    Route::delete('productos/{producto}', [ProductoController::class, 'destroy'])->name('producto.destroy');

    // Rutas para la administraccion del cliente
    Route::post('/cliente', [ClienteController::class, 'store'])->name('cliente.store');
});


Route::post('/login', [UserController::class, 'login'])->name('user.login');
