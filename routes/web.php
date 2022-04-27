<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Return views
Route::get('/', function () {

    return view('home',[
        'products' => Product::select('*')->orderBy('created_at','desc')->limit(5)->get(),
        'categories' => Category::all(),
    ]);

});
Route::get('/addCategory',[CategoryController::class,'create']);
Route::get('/addProduct',[ProductController::class,'create']);
Route::get('/updateCategory/{name}',[CategoryController::class,'edit']);
Route::get('/updateProduct/{name}',[ProductController::class,'edit']);

// Handle requests

// Category
Route::get('/categories', [CategoryController::class, 'display']);
// Route::get('/category/{id}', [CategoryController::class, 'show']);
Route::post('/category', [CategoryController::class, 'store']);
Route::put('/category/{id}', [CategoryController::class, 'update']);
Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

// Product
Route::get('/products', [ProductController::class, 'display']);
// Route::get('/product/{id}', [ProductController::class, 'show']);
Route::post('/product', [ProductController::class, 'store']);
Route::put('/product/{id}', [ProductController::class, 'update']);
Route::delete('/product/{id}', [ProductController::class, 'destroy']);