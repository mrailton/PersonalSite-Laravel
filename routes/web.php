<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Articles\ListArticlesController as AdminListArticlesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Articles\ListArticlesController;
use App\Http\Controllers\Articles\ShowArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ProcessLoginController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('index');
Route::get('/blog', ListArticlesController::class)->name('articles.list');
Route::get('/blog/{article:slug}', ShowArticleController::class)->name('articles.show');

Route::group(['prefix' => '/admin', 'as' => 'admin.', 'middleware' => 'auth'], function (): void {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::group(['prefix' => '/articles', 'as' => 'articles.'], function (): void {
        Route::get('/', AdminListArticlesController::class)->name('list');
    });
});

Route::group(['prefix' => '/auth', 'as' => 'auth.'], function (): void {
    Route::get('/login', LoginController::class)->name('login');
    Route::post('/login', ProcessLoginController::class)->name('login.process');
    Route::post('/logout', LogoutController::class)->name('logout');
});
