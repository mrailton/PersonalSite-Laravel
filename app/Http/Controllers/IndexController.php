<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class IndexController
{
    public function __invoke(Request $request): View
    {
        $articles = Article::query()->where('published_at', '<', date('Y-m-d H:i:s'))->orderByDesc('published_at')->limit(3)->get();

        return view('index', ['articles' => $articles]);
    }
}
