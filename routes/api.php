<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::get('user-profile', 'getProfile');
});

Route::middleware('auth:api')->group(function () {

    Route::controller(CategoryController::class)->prefix('categories')->group(function () {

        Route::get('/index', 'index');
        Route::post('/store', 'store');
        Route::get('/edit', 'edit');
        Route::post('/update', 'update');
        Route::post('/delete', 'delete');

    });
    Route::controller(ArticleController::class)->prefix('articles')->group(function () {

        Route::get('/index', 'index');
        Route::post('/store', 'store');
        Route::post('/update', 'update');
        Route::post('/delete', 'delete');
        Route::get('/category', 'getCategoryArticle');
        Route::get('/slug', 'getSlugArticle');
        Route::get('/user', 'getUserArticle');

    });

});
