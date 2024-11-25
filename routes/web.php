<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Models\Fichero;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('welcome');
Route::get('/login', [AuthController::class, 'showLogin'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
//Route::get('/home', [HomeController::class, 'index'])->name('home');

// ruta en web.php para manejar la solicitud de vista previa
Route::get('/files/preview/{file}', [FileController::class, 'preview'])->name('files.preview');
Route::get('/files/stream/{file}', [FileController::class, 'stream'])->name('files.stream');

// Ruta para descargar archivos sin autenticación
Route::get('/download/{file}', [FileController::class, 'download'])->name('files.download');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Ruta para subir archivos
    Route::post('/upload', [FileController::class, 'upload'])->name('files.upload');
    
    // Ruta para eliminar (soft delete) archivosº
    Route::get('/delete/{file}', [FileController::class, 'delete'])
        ->name('files.delete')
        ->can('delete', 'file'); // Política de eliminación
});

//Ruta para la restauracion de archivos
Route::get('/restore/{file}', [FileController::class, 'restore'])->name('files.restore');

// Ruta para la eliminación permanente de archivos
Route::get('/force-delete/{file}', [FileController::class, 'forceDelete'])->name('files.forceDelete');

// Rutas para el acceso de todos admin y users, esta ruta sera una trampa para que el admin pueda acceder a algunos sitios pero el user no
Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

//Ruta edicion de metadatos
Route::get('/files/edit/{id}', [FileController::class, 'showEditMetadataForm'])->name('files.editMetadata');
Route::post('/files/{id}/update', [FileController::class, 'updateMetadata'])->name('files.updateMetadata');
Route::get('/stream/{file}', [FileController::class, 'stream'])->name('files.preview');

//Ruta para formulario de busqueda
Route::get('/search', [FileController::class, 'search'])->name('files.search');

//Ruta para compartir archivos
//Route::post('/files/{id}/share', [FileController::class, 'share'])->name('files.share');
Route::post('/files/{fichero}/share', [FileController::class, 'share'])->name('files.share');

//Ruta para reportes 
Route::get('/report/file-activity/{file}', [ReportController::class, 'fileActivityReport'])->name('report.fileActivity');

//Ruta para estadisticas
Route::get('/report/usage-statistics', [ReportController::class, 'usageStatistics'])->name('report.usageStatistics');









