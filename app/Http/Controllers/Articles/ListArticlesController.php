<?php

declare(strict_types=1);

namespace App\Http\Controllers\Articles;

use App\Models\Article;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ListArticlesController
{
    public function __invoke(Request $request): View
    {
        $articles = Article::query()
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at')
            ->paginate(10);

        return view('articles.list', ['articles' => $articles]);
    }
}
