<?php

use App\Http\Controllers\AlbumController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::resource('album', AlbumController::class);
Route::post('/delete-image', [AlbumController::class, 'image_destroy'])->name('image.destroy');
Route::post('/upload', [AlbumController::class, 'upload'])->name('upload');
Route::post('/upload-temp', [AlbumController::class, 'uploadTemp'])->name('upload.temp');
Route::delete('/revert', [AlbumController::class, 'revertUpload'])->name('revert.upload');

Route::get('/', function () {
    return view('welcome');

});
