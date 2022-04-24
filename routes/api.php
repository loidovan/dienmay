<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PostController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::resource('accounts', AccountController::class);
    Route::resource('roles', RoleController::class);
    Route::post('change-password', [AuthController::class, 'changePasswordFirstLogin']);
    Route::resource('categories', CategoryController::class);
    Route::resource('types', TypeController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('products', ProductController::class);
    Route::resource('posts', PostController::class);
    Route::post('/upload', [ImageController::class, 'store'])->name('upload');
    Route::get('/media/{product}', [ImageController::class, 'getImages'])->name('product.images');
    Route::post('/products/upload_images', [ImageController::class, 'uploadImages'])->name('products.upload_images');
    Route::post('/products/delete_images', [ImageController::class, 'deleteImages'])->name('products.delete_images');
    Route::post('/posts/deleteImage', [PostController::class, 'deleteImage']);
    Route::post('/products/filters', [ProductController::class, 'filterProducts']);

    Route::post('logout', [AuthController::class, 'logout']);
});
