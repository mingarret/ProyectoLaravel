<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Models\Fichero;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Ruta para subir archivos
    Route::post('/upload', [FileController::class, 'upload'])
        ->name('files.upload')
        ->can('upload', Fichero::class); // Política de subida
    
    // Ruta para descargar archivos
    Route::get('/download/{file}', [FileController::class, 'download'])
        ->name('files.download'); // Ruta de descarga sin política explícita
    
    // Ruta para eliminar (soft delete) archivos
    Route::get('/delete/{file}', [FileController::class, 'delete'])
        ->name('files.delete')
        ->can('delete', 'file'); // Política de eliminación
    
    // Ruta para restaurar archivos eliminados
    Route::get('/restore/{file}', [FileController::class, 'restore'])
        ->name('files.restore')
        ->can('restore', 'file'); // Política de restauración
    
    // Ruta para eliminar permanentemente archivos
    Route::get('/force-delete/{file}', [FileController::class, 'forceDelete'])
        ->name('files.forceDelete')
        ->can('forceDelete', 'file'); // Política de eliminación permanente
});

    Route::middleware(['auth', 'can:manage-users'])->group(function () {
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
        Route::post('/users/{user}/regenerate-password', [UserController::class, 'regeneratePassword'])->name('users.regenerate-password');
    });
