<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Articles;

use App\Models\Article;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ListArticlesController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $articles = Article::query()->paginate(10);

        return view('admin.articles.list', ['articles' => $articles]);
    }
}
