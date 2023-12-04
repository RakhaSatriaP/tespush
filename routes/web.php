<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




// Route::group(['middleware' => ['role:owner']], function () {
   
//     Route::get('/category', [App\Http\Controllers\CategoryController::class, 'index'])->name('category.index');
//     Route::get('/category/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('category.create');
    
// });

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/category', [App\Http\Controllers\CategoryController::class, 'index'])->name('category.index');
    Route::post('/category/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('category.create');
    Route::delete('/category/destroy', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('category.destroy');
    Route::put('/category/update', [App\Http\Controllers\CategoryController::class, 'update'])->name('category.update');

    
    
   
    
    
    
    
});

Route::middleware('auth')->group(function (){
    
    Route::get('/transaction/addToCart/{id}', [App\Http\Controllers\TransactionController::class, 'addToCart'])->name('product.addToCart');
    Route::get('/transaction/minToCart/{id}', [App\Http\Controllers\TransactionController::class, 'minFromCart'])->name('transaction.minFromCart');
    Route::get('/transaction/deleteToCart/{id}', [App\Http\Controllers\TransactionController::class, 'deleteFromCart'])->name('transaction.deleteFromCart');
    Route::resource('transaction', App\Http\Controllers\TransactionController::class);
    
    Route::resource('product', App\Http\Controllers\ProductController::class);
});

