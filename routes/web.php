<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\UserController;
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

Route::get('/', [UserController::class, 'index']);

Route::prefix('admin')
    ->name('admin.')
    ->group(function(){
        Route::get('/', function(){
            echo "Dash Board Admin";
        })->name('index');

        Route::resource('products', ProductController::class);
    });
