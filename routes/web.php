<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Articles\ListArticlesController as AdminListArticlesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Articles\ListArticlesController;
use App\Http\Controllers\Articles\ShowArticleController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('index');
Route::get('/blog', ListArticlesController::class)->name('articles.list');
Route::get('/blog/{article:slug}', ShowArticleController::class)->name('articles.show');

Route::group(['prefix' => '/admin'], function (): void {
    Route::get('/', DashboardController::class)->name('admin.dashboard');

    Route::group(['prefix' => '/articles'], function (): void {
        Route::get('/', AdminListArticlesController::class)->name('admin.articles.list');
    });
});

Route::group(['prefix' => '/auth', 'as' => 'auth.'], function (): void {
    Route::post('/logout', LogoutController::class)->name('logout');
});
