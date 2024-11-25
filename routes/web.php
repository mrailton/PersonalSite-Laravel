<?php

declare(strict_types=1);

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('index');
Route::get('/blog', IndexController::class)->name('articles.list');
